<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'bookingMenus']);

        // Xử lý tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('number_of_people', 'like', "%$search%")
                  ->orWhereHas('user', function($query) use ($search) {
                      $query->where('name', 'like', "%$search%");
                  });
            });
        }

        // Xử lý lọc theo trạng thái
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Xử lý lọc theo trạng thái thanh toán
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Xử lý lọc theo ngày
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('booking_date', $request->date);
        }

        // Xử lý sắp xếp
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');
        
        switch($sortField) {
            case 'booking_date':
            case 'number_of_people':
            case 'total_amount':
            case 'status':
            case 'payment_status':
            case 'id':
                $query->orderBy($sortField, $sortDirection);
                break;
            case 'booking_type':
                $query->orderBy(
                    Booking::select('booking_type')
                        ->whereColumn('id', 'bookings.id'),
                    $sortDirection
                );
                break;
        }

        $bookings = $query->paginate(10)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        try {
            DB::transaction(function() use ($request, $booking) {
                // Nếu là đơn chỉ đặt bàn và trạng thái chuyển sang hoàn thành
                if ($booking->booking_type === 'only_table' && $request->status === 'completed') {
                    $booking->status = 'completed';
                    $booking->payment_status = 'fully_paid';
                    $booking->full_payment_time = now();
                    $booking->save();
                } else {
                    if ($request->status == 'cancelled' && $booking->payment_status == 'pending') {
                        $booking->payment_status = 'failed';
                    }
                    $booking->status = $request->status;
                    $booking->save();
                }
            });

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật trạng thái đặt bàn thành công'
            ]);
        } catch (\Exception $e) {
            \Log::error('Lỗi cập nhật trạng thái: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'bookingMenus.menu'])
                         ->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Kiểm tra không cho xóa đơn đã thanh toán
            if ($booking->payment_status == 'paid') {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể xóa đơn đã thanh toán'
                ], 400);
            }

            $booking->bookingMenus()->delete(); // Xóa các món ăn liên quan
            $booking->delete();
            
            return response()->json([
                'status' => true,
                'message' => 'Xóa đặt bàn thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi xóa đặt bàn: ' . $e->getMessage()
            ], 500);
        }
    }

    public function completePayment(Booking $booking)
    {
        try {
            if ($booking->payment_status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng chưa thanh toán tiền cọc'
                ]);
            }

            if ($booking->status !== 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng chưa được xác nhận'
                ]);
            }

            // Tính số tiền còn lại(80%)
            $finalAmount = $booking->total_amount * 0.8;

            // Lưu thông tin thanh toán cuối
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $finalAmount,
                'payment_type' => 'final',
                'payment_method' => 'cash', 
                'status' => 'completed',
                'payment_time' => now(),
            ]);

            // Cập nhật trạng thái booking
            $booking->update([
                'payment_status' => 'completed',
                'final_payment_time' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thanh toán thành công'
            ]);
        } catch (\Exception $e) {
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý thanh toán'
            ]);
        }
    }

    public function edit($id)
    {
        $booking = Booking::with(['bookingMenus.menu', 'user'])->findOrFail($id);
        $menus = Menu::active()->with('category')->get();
        $categories = Category::all();
        
        return view('admin.bookings.edit', compact('booking', 'menus', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Cập nhật thông tin cơ bản
            $booking->update([
                'number_of_people' => $request->number_of_people,
                'special_request' => $request->special_request,
            ]);

            // Xử lý cập nhật món ăn
            if ($request->has('menus')) {
                // Xóa các món cũ
                $booking->bookingMenus()->delete();
                
                $totalAmount = 0;
                foreach ($request->menus as $menuId => $quantity) {
                    if ($quantity > 0) {
                        $menu = Menu::findOrFail($menuId);
                        $subtotal = $menu->price * $quantity;
                        $totalAmount += $subtotal;

                        $booking->bookingMenus()->create([
                            'menu_id' => $menuId,
                            'quantity' => $quantity,
                            'price' => $menu->price,
                            'subtotal' => $subtotal
                        ]);
                    }
                }
                
                // Cập nhật tổng tiền
                $booking->total_amount = $totalAmount;
                $booking->save();
            }

            DB::commit();
            return redirect()
                ->route('admin.bookings.show', $booking->id)
                ->with('success', 'Cập nhật đơn đặt bàn thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật đơn đặt bàn: ' . $e->getMessage());
        }
    }

    public function confirmFullPayment(Booking $booking)
    {
        try {
            DB::transaction(function() use ($booking) {
                $booking->update([
                    'status' => 'completed', // Chuyển trạng thái đơn sang hoàn thành
                    'payment_status' => 'fully_paid', // Cập nhật trạng thái thanh toán
                    'full_payment_time' => now() // Lưu thời gian thanh toán đầy đủ
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật trạng thái thanh toán'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
