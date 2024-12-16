<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingMenu;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class BookingController extends Controller
{
    // Hiển thị danh sách booking của user
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $bookings = $user->bookings()->latest()->paginate(10);

        return view('bookings.index', [
            'bookings' => $bookings
        ]);
    }

    // Hiển thị form tạo booking mới
    public function create()
    {
        $categories = Category::with(['menu' => function ($query) {
            $query->where('status', 1)
                ->orderBy('position');
        }])
            ->where('status', 1)
            ->get();

        return view('front.booking', compact('categories'));
    }

    // Lưu booking mới
    public function store(Request $request)
    {
        Log::info('Booking request data:', $request->all());

        try {
            // Validate dữ liệu
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => [
                    'required',
                    'string',
                    'regex:/^([0-9\s\-\+\(\)]*)$/',
                    'min:10',
                    'max:11'
                ],
                'booking_date' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        try {
                            // Parse thời gian và đặt múi giờ rõ ràng
                            $bookingDateTime = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $value, 'Asia/Ho_Chi_Minh');
                            $currentTime = now()->setTimezone('Asia/Ho_Chi_Minh');
                            $minTime = $currentTime->copy()->addMinutes(30);
                            // Kiểm tra thời gian trong quá khứ
                            if ($bookingDateTime->lt($currentTime)) {
                                $fail('Không thể đặt bàn với thời gian trong quá khứ.');
                                return;
                            }

                            // Kiểm tra thời gian tối thiểu 30 phút
                            if ($bookingDateTime->lt($minTime)) {
                                $fail('Vui lòng đặt bàn trước thời điểm hiện tại ít nhất 30 phút.');
                            }
                        } catch (\Exception $e) {
                            Log::error('Booking date validation error:', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);
                            $fail('Thời gian đặt bàn không hợp lệ.');
                        }
                    }
                ],
                'number_of_people' => 'required|integer|min:1|max:10',
                'special_request' => 'nullable|string|max:500',
                'booking_type' => 'required|in:only_table,with_menu',

                // Sửa lại validation cho menu items
                'menu_items' => 'required_if:booking_type,with_menu|array',
                'menu_items.*.selected' => 'required_if:booking_type,with_menu|string|in:on',  // Chấp nhận giá trị "on"
                'menu_items.*.quantity' => 'required_if:menu_items.*.selected,on|integer|min:1|max:10',
            ], []);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();

            Log::info('Dữ liệu đã qua validation:', $validated);

            // Lưu dữ liệu vào session
            session(['booking_data' => $validated]);
            Log::info('Dữ liệu đã lưu vào session:', ['booking_data' => $validated]);

            
            return redirect()->route('front.booking.show-confirm');
        } catch (\Exception $e) {
            Log::error('Lỗi trong quá trình store booking:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi đặt bàn. Vui lòng thử lại.')
                ->withInput();
        }
    }

    // Hủy booking
    public function cancel(Booking $booking)
    {
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->back()->with('success', 'Đã hủy đơn đặt bàn thành công!');
    }

    public function showConfirmation(Request $request)
    {


        $bookingData = session('booking_data');


        if (!$bookingData) {
            Log::warning('Không tìm thấy dữ liệu đặt bàn trong session.');
            return redirect()->route('front.booking')->with('error', 'Không tìm thấy thông tin đặt bàn.');
        }

        // Chỉ kiểm tra 'menu_items' nếu là 'with_menu'
        if ($bookingData['booking_type'] == 'with_menu') {
            if (!isset($bookingData['menu_items'])) {
                
                return redirect()->route('front.booking')->with('error', 'Thiếu thông tin món ăn.');
            }
        }



        return view('front.booking.confirm', compact('bookingData'));
    }



    public function processPayment(Request $request)
    {
        Log::info('--- Bắt đầu xử lý thanh toán ---', []);
        Log::info('Dữ liệu request:', $request->all());
        try {
            $booking = Booking::findOrFail($request->booking_id);
            Log::info('Tìm thấy booking:', ['booking_id' => $booking->id]);

            // Cập nhật trạng thái sang processing
            $booking->update([
                'payment_status' => 'processing'
            ]);
            Log::info('Đã cập nhật trạng thái booking thành processing.', []);

            // Lưu thời gian bắt đầu thanh toán
            session(['payment_start_time' => now()]);
            Log::info('Đã lưu thời gian bắt đầu thanh toán vào session.', []);

            // Lấy thông tin cấu hình từ file config
            $vnp_Url = config('vnpay.url');
            $vnp_TmnCode = config('vnpay.tmn_code');
            $vnp_HashSecret = config('vnpay.hash_secret');
            $vnp_ReturnUrl = config('vnpay.return_url');

            Log::info('VNPay Config:', [
                'url' => $vnp_Url,
                'tmn_code' => $vnp_TmnCode,
                'return_url' => $vnp_ReturnUrl
            ]);

            // Tạo mã giao dịch với format: bookingId-timestamp
            $vnp_TxnRef = $booking->id . '-' . time();
            Log::info('Mã giao dịch VNPay:', ['vnp_txn_ref' => $vnp_TxnRef]);

            // Lưu mã giao dịch vào session
            session(['vnpay_booking_id' => $booking->id]);
            Log::info('Đã lưu mã giao dịch VNPay vào session.', []);

            //Tính cọc 20% tổng tiền
            $depositAmount = ceil($booking->total_amount * 0.2);
            $vnp_Amount = $depositAmount*100;//Do VN yêu cầu *100
            Log::info('Số tiền thanh toán:', ['vnp_amount' => $vnp_Amount]);

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => request()->ip(),
                "vnp_Locale" => "vn",
                "vnp_OrderInfo" => "Thanh toan dat ban - " . $vnp_TxnRef,
                "vnp_OrderType" => "billpayment",
                "vnp_ReturnUrl" => $vnp_ReturnUrl,
                "vnp_TxnRef" => $vnp_TxnRef
            );
            Log::info('Dữ liệu gửi đến VNPay:', $inputData);

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            Log::info('URL VNPay:', ['url' => $vnp_Url]);

            // Chuyển hướng đến VNPay
            Log::info('Chuyển hướng đến VNPay.', []);
            return redirect()->away($vnp_Url);
        } catch (\Exception $e) {
            Log::error('Lỗi trong quá trình xử lý thanh toán:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Nếu có lỗi, cập nhật trạng thái về failed
            if (isset($booking)) {
                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled'
                ]);
                Log::info('Đã cập nhật trạng thái booking thành failed.', []);
            }

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán');
        }
    }

    public function edit(Booking $booking)
    {
        // Kiểm tra quyền sửa
        if (!$booking->can_edit) {
            return redirect()->route('bookings.index')
                ->with('error', 'Không thể sửa đơn đặt bàn này.');
        }

        // Lấy danh sách menu nếu là đặt bàn kèm món
        $menus = [];
        if ($booking->booking_type === 'with_menu') {
            $menus = Menu::active()->get();
        }

        return view('bookings.edit', compact('booking', 'menus'));
    }

    public function update(Request $request, Booking $booking)
    {
        // Kiểm tra quyền sửa
        if (!$booking->can_edit) {
            return redirect()->route('bookings.index')
                ->with('error', 'Không thể sửa đơn đặt bàn này.');
        }

        // Validate và cập nhật tương tự như store()
        try {
            DB::beginTransaction();

            // Cập nhật thông tin booking
            $booking->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'booking_date' => $request->booking_date,
                'number_of_people' => $request->number_of_people,
                'special_request' => $request->special_request
            ]);

            // Nếu là đặt bàn kèm món, cập nhật menu items
            if ($booking->booking_type === 'with_menu') {
                // Xóa menu items cũ
                $booking->bookingMenus()->delete();

                // Thêm menu items mới
                $totalAmount = 0;
                foreach ($request->menu_items as $menuId => $item) {
                    if (isset($item['selected']) && $item['selected'] === 'on') {
                        $menu = Menu::findOrFail($menuId);
                        $subtotal = $menu->price * $item['quantity'];

                        BookingMenu::create([
                            'booking_id' => $booking->id,
                            'menu_id' => $menuId,
                            'quantity' => $item['quantity'],
                            'price' => $menu->price,
                            'subtotal' => $subtotal
                        ]);

                        $totalAmount += $subtotal;
                    }
                }

                $booking->update(['total_amount' => $totalAmount]);
            }

            DB::commit();

            return redirect()->route('bookings.index')
                ->with('success', 'Cập nhật đơn đặt bàn thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật đơn đặt bàn.')
                ->withInput();
        }
    }

    public function confirm(Request $request)
    {
        Log::info('--- Bắt đầu phương thức confirm ---', []);

        // Kiểm tra đăng nhập
        if (!auth()->check()) {
            Log::warning('Người dùng chưa đăng nhập.');
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $userId = auth()->id();
        Log::info('User ID:', ['user_id' => $userId]);

        $bookingData = session('booking_data');
        Log::info('Dữ liệu booking từ session:', $bookingData);

        if (!$bookingData) {
            Log::warning('Không tìm thấy dữ liệu đặt bàn trong session.');
            return redirect()->route('front.booking')->with('error', 'Không tìm thấy thông tin đặt bàn.');
        }


        if ($request->session()->has('user_id')) {
            Log::info('User ID tồn tại trong session:', ['user_id' => $request->session()->get('user_id')]);
        } else {
            Log::error('User ID không tồn tại trong session.');
        }

        // Duy trì session đăng nhập
        if (auth()->check()) {
            $request->session()->put('user_last_activity', time());
            Log::info('Duy trì session đăng nhập cho user ID:', ['user_id' => auth()->id()]);
        }
        // --- Kết thúc phần kiểm tra và duy trì session ---

        //Lưu session
        session()->forget('booking_data');
        Log::info('Đã xóa session booking_data.', []);

        // Tạo booking mới
        $booking = new Booking();
        $booking->user_id = $userId;
        $booking->name = $bookingData['name'];
        $booking->phone = $bookingData['phone'];
        $booking->booking_date = $bookingData['booking_date'];
        $booking->number_of_people = $bookingData['number_of_people'];
        $booking->status = 'pending'; 
        $booking->special_request = $bookingData['special_request'] ?? null;
        $booking->booking_type = $bookingData['booking_type'];

        // Xử lý riêng cho từng loại đặt bàn
        if ($bookingData['booking_type'] == 'only_table') {
            // Chỉ đặt bàn
            $booking->total_amount = 0;
            $booking->save();
            Log::info('Đã tạo booking (chỉ đặt bàn):', ['booking_id' => $booking->id]);

            Log::info('Đặt bàn thành công (không cần thanh toán).', []);
            return redirect()->route('front.booking.success')->with('success', 'Đặt bàn thành công!');
        } elseif ($bookingData['booking_type'] == 'with_menu') {
            // Đặt bàn kèm món ăn
            // Tính tổng tiền
            $totalAmount = 0;
            foreach ($bookingData['menu_items'] as $menuId => $item) {
                if (isset($item['selected']) && $item['selected'] == 'on') {
                    $menu = Menu::find($menuId);
                    $totalAmount += $menu->price * $item['quantity'];
                }
            }
            $booking->total_amount = $totalAmount;

            $booking->save();
            Log::info('Đã tạo booking (đặt bàn và món ăn):', ['booking_id' => $booking->id]);

            // Lưu thông tin menu items vào bảng booking_menus
            foreach ($bookingData['menu_items'] as $menuId => $item) {
                if (isset($item['selected']) && $item['selected'] == 'on') {
                    $menu = Menu::find($menuId);

                    $bookingMenu = new BookingMenu();
                    $bookingMenu->booking_id = $booking->id;
                    $bookingMenu->menu_id = $menuId;
                    $bookingMenu->quantity = $item['quantity'];
                    $bookingMenu->price = $menu->price;
                    $bookingMenu->subtotal = $menu->price * $item['quantity'];
                    $bookingMenu->save();
                    
                }
            }

            // Lưu booking_id vào session để sử dụng trong VNPayController
            session(['vnpay_booking_id' => $booking->id]);
            Log::info('Đã lưu booking_id vào session:', ['vnpay_booking_id' => $booking->id]);

            // Chuyển hướng đến trang thanh toán VNPay
            Log::info('Chuyển hướng đến trang thanh toán VNPay.', []);
            return redirect()->route('front.booking.processPayment', ['booking_id' => $booking->id]);
        } else {
            // Trường hợp lỗi (không xác định được loại đặt bàn)
            Log::error('Loại đặt bàn không hợp lệ:', ['booking_type' => $bookingData['booking_type']]);
            return redirect()->route('front.booking')->with('error', 'Loại đặt bàn không hợp lệ.');
        }

        Log::info('--- Kết thúc phương thức confirm ---', []);
    }

    public function success()
    {
        return view('front.booking.success');
    }


    public function vnpayReturn(Request $request)
    {
        Log::info('--- Nhận phản hồi từ VNPay ---', []);
        Log::info('Dữ liệu phản hồi từ VNPay:', $request->all());

        try {
            // Trích xuất booking ID từ vnp_TxnRef
            $txnRef = $request->vnp_TxnRef;
            $bookingId = explode('-', $txnRef)[0];
            Log::info('Booking ID từ VNPay:', ['booking_id' => $bookingId]);

            if (!$bookingId) {
                Log::error('Không tìm thấy mã đơn đặt bàn trong phản hồi từ VNPay.');
                throw new \Exception('Không tìm thấy mã đơn đặt bàn');
            }

            $booking = Booking::findOrFail($bookingId);
            Log::info('Tìm thấy booking:', ['booking_id' => $booking->id]);

            // Kiểm tra chữ ký và xử lý response
            if ($this->validateVNPayResponse($request)) {
                Log::info('Chữ ký VNPay hợp lệ.', []);

                if ($request->vnp_ResponseCode == "00") {
                    // Thanh toán thành công
                    Log::info('Thanh toán thành công cho booking:', ['booking_id' => $bookingId]);

                    // Tính tiền đặt cọc (20%)
                    $depositAmount = ceil($booking->total_amount * 0.2);

                    // Lưu thông tin thanh toán
                    $payment = Payment::create([
                        'booking_id' => $booking->id,
                        'amount' => $depositAmount,
                        'payment_type' => 'deposit',
                        'payment_method' => 'vnpay',
                        'transaction_id' => $request->vnp_TransactionNo,
                        'transaction_ref' => $request->vnp_TxnRef,
                        'status' => 'completed',
                        'payment_time' => now(),
                    ]);
                    Log::info('Đã lưu thông tin thanh toán thành công:', ['payment_id' => $payment->id]);

                    // Cập nhật trạng thái booking
                    $booking->update([
                        'payment_status' => 'paid',
                        // Không thay đổi trạng thái đặt bàn, giữ nguyên là 'pending'
                        // để chờ admin xác nhận
                    ]);
                    // Duy trì session đăng nhập sau khi thanh toán
                    if (auth()->check()) {
                        $request->session()->put('user_last_activity', time());
                        Log::info('Duy trì session đăng nhập cho user ID:', ['user_id' => auth()->id()]);
                    }
                    Log::info('Đã cập nhật trạng thái booking thành paid.', []);

                    return redirect()->route('front.booking.success')
                        ->with('success', 'Thanh toán thành công! Vui lòng chờ nhà hàng xác nhận đơn của bạn.');
                } else {
                    // Thanh toán thất bại
                    Log::info('Thanh toán thất bại cho booking:', ['booking_id' => $bookingId]);

                    // Lưu thông tin thanh toán thất bại
                    Payment::create([
                        'booking_id' => $booking->id,
                        'amount' => ceil($booking->total_amount * 0.2),
                        'payment_type' => 'deposit',
                        'payment_method' => 'vnpay',
                        'transaction_id' => $request->vnp_TransactionNo,
                        'transaction_ref' => $request->vnp_TxnRef,
                        'status' => 'failed',
                        'payment_time' => now(),
                    ]);
                    Log::info('Đã lưu thông tin thanh toán thất bại.', []);

                    $booking->update([
                        'payment_status' => 'failed',
                        'status' => 'cancelled'
                    ]);
                    Log::info('Đã cập nhật trạng thái booking thành failed.', []);

                    return redirect()->route('front.booking')
                        ->with('error', 'Thanh toán không thành công. Vui lòng thử lại.');
                }
            } else {
                Log::error('Chữ ký VNPay không hợp lệ.');

                // Chữ ký không hợp lệ
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => ceil($booking->total_amount * 0.2),
                    'payment_type' => 'deposit',
                    'payment_method' => 'vnpay',
                    'transaction_ref' => $request->vnp_TxnRef,
                    'status' => 'failed',
                    'payment_time' => now(),
                ]);
                Log::info('Đã lưu thông tin thanh toán với trạng thái chữ ký không hợp lệ.', []);

                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled'
                ]);
                Log::info('Đã cập nhật trạng thái booking thành failed (chữ ký không hợp lệ).', []);

                return redirect()->route('front.booking')
                    ->with('error', 'Chữ ký không hợp lệ!');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi trong quá trình xử lý phản hồi từ VNPay:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('front.booking')
                ->with('error', 'Có lỗi xảy ra trong quá trình xử lý thanh toán');
        }
    }
    // Thêm một command để tự động hủy các đơn hàng quá hạn thanh toán
    // app/Console/Commands/CancelPendingPayments.php
    public function handle()
    {
        $timeLimit = now()->subMinutes(15); // 15 phút

        $pendingBookings = Booking::where('payment_status', 'processing')
            ->where('updated_at', '<=', $timeLimit)
            ->get();

        foreach ($pendingBookings as $booking) {
            $booking->update([
                'payment_status' => 'failed',
                'status' => 'cancelled'
            ]);

            Log::info('Cancelled expired payment for booking: ' . $booking->id);
        }
    }

    public function getBookingDetail(Request $request, $id)
    {
        try {
            $booking = Booking::with(['bookingMenus.menu'])
                ->where('user_id', auth()->id())
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => [
                    'booking' => $booking,
                    'statusText' => [
                        'pending' => 'Đang chờ xác nhận',
                        'confirmed' => 'Đã xác nhận',
                        'cancelled' => 'Đã hủy',
                        'completed' => 'Hoàn thành'
                    ],
                    'html' => view('bookings.partials.detail-modal', compact('booking'))->render()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể lấy thông tin đơn đặt bàn'
            ], 404);
        }
    }

    private function validateVNPayResponse(Request $request)
    {
        // Lấy vnp_SecureHash từ response
        $vnp_SecureHash = $request->vnp_SecureHash;

        // Lấy các tham số trở về từ VNPay
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        // Xóa vnp_SecureHash để tính toán hash mới
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        // Tạo chuỗi hash data
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        // Lấy vnp_HashSecret từ config
        $vnp_HashSecret = config('vnpay.hash_secret');

        // Tính toán checksum
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // So sánh checksum từ VNPay với checksum tính toán
        return $vnp_SecureHash === $secureHash;
    }
}
