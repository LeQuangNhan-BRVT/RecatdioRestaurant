<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;  // Thêm Log để debug

// class VNPayController extends Controller
// {
//     public function return(Request $request)
//     {
//         Log::info('VNPay Return Data:', $request->all());

//         // Lưu user_id từ session nếu có
//         $userId = auth()->id();
//         Log::info('Current User ID: ' . $userId);

//         // Kiểm tra checksum
//         $vnp_SecureHash = $request->vnp_SecureHash;
//         $inputData = array();
//         foreach ($request->all() as $key => $value) {
//             if (substr($key, 0, 4) == "vnp_") {
//                 $inputData[$key] = $value;
//             }
//         }
//         unset($inputData['vnp_SecureHash']);
//         ksort($inputData);
//         $hashData = "";
//         $i = 0;
//         foreach ($inputData as $key => $value) {
//             if ($i == 1) {
//                 $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value); 
//             } else {
//                 $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
//                 $i = 1;
//             }
//         }

//         $secureHash = hash_hmac('sha512', $hashData, config('vnpay.hash_secret'));

//         if ($secureHash == $vnp_SecureHash) {
//             // Lấy booking_id từ session
//             $bookingId = session('vnpay_booking_id');
//             Log::info('Looking for booking ID: ' . $bookingId);
            
//             if (!$bookingId) {
//                 Log::error('No booking ID found in session');
//                 return redirect()->route('front.booking')
//                     ->with('error', 'Không tìm thấy thông tin đặt bàn!');
//             }

//             $booking = Booking::find($bookingId);
            
//             if (!$booking) {
//                 Log::error('Booking not found: ' . $bookingId);
//                 return redirect()->route('front.booking')
//                     ->with('error', 'Không tìm thấy thông tin đặt bàn!');
//             }
            
//             if ($request->vnp_ResponseCode == "00") {
//                 // Thanh toán thành công
//                 $booking->update([
//                     'payment_status' => 'paid',
//                     'status' => 'confirmed'
//                 ]);
                
//                 // Chỉ xóa session liên quan đến thanh toán
//                 $request->session()->forget('vnpay_booking_id');
                
//                 Log::info('Payment successful for booking: ' . $bookingId);
                
//                 // Kiểm tra và duy trì session đăng nhập
//                 if ($userId) {
//                     auth()->loginUsingId($userId);
//                 }
                
//                 return redirect()->route('front.booking.success')
//                     ->with('success', 'Đặt bàn và thanh toán thành công!');
//             } else {
//                 // Thanh toán thất bại
//                 $booking->update([
//                     'payment_status' => 'failed',
//                     'status' => 'cancelled'
//                 ]);
                
//                 // Chỉ xóa session liên quan đến thanh toán
//                 $request->session()->forget('vnpay_booking_id');
                
//                 Log::error('Payment failed for booking: ' . $bookingId . ' with response code: ' . $request->vnp_ResponseCode);
                
//                 // Kiểm tra và duy trì session đăng nhập
//                 if ($userId) {
//                     auth()->loginUsingId($userId);
//                 }
                
//                 return redirect()->route('front.booking')
//                     ->with('error', 'Thanh toán không thành công. Vui lòng thử lại.');
//             }
//         } else {
//             Log::error('Invalid hash');
            
//             // Kiểm tra và duy trì session đăng nhập
//             if ($userId) {
//                 auth()->loginUsingId($userId);
//             }
            
//             return redirect()->route('front.booking')
//                 ->with('error', 'Chữ ký không hợp lệ!');
//         }
//     }
// }
class VNPayController extends Controller
{
    public function return(Request $request)
    {
        Log::info('VNPay Return Data:', $request->all());

        $userId = auth()->id();
        Log::info('Current User ID: ' . $userId);

        // Kiểm tra checksum
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, config('vnpay.hash_secret'));

        if ($secureHash == $vnp_SecureHash) {
            // Lấy booking_id từ session
            $bookingId = session('vnpay_booking_id');
            Log::info('Looking for booking ID: ' . $bookingId);

            if (!$bookingId) {
                Log::error('No booking ID found in session');
                return redirect()->route('front.booking')
                    ->with('error', 'Không tìm thấy thông tin đặt bàn!');
            }

            $booking = Booking::find($bookingId);

            if (!$booking) {
                Log::error('Booking not found: ' . $bookingId);
                return redirect()->route('front.booking')
                    ->with('error', 'Không tìm thấy thông tin đặt bàn!');
            }

            if ($request->vnp_ResponseCode == "00") {
                // Thanh toán thành công
                Log::info('Thanh toán thành công cho booking:', ['booking_id' => $bookingId]);

                // Tạo bản ghi Payment
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_amount * 0.2, // Giả sử thanh toán trước 20%
                    'payment_type' => 'deposit', // Loại thanh toán: đặt cọc
                    'payment_method' => 'vnpay',
                    'transaction_id' => $request->vnp_TransactionNo,
                    'transaction_ref' => $request->vnp_TxnRef, // Mã tham chiếu từ VNPay
                    'status' => 'success',
                    'payment_time' => now(),
                ]);
                Log::info('Đã lưu thông tin thanh toán thành công.');

                // Cập nhật trạng thái booking
                $booking->update([
                    'payment_status' => 'paid',
                    // Không thay đổi trạng thái đặt bàn, giữ nguyên là 'pending'
                    // để chờ admin xác nhận
                ]);
                Log::info('Đã cập nhật trạng thái booking thành paid.');

                // Xóa session
                $request->session()->forget('vnpay_booking_id');
                $request->session()->forget('booking_data'); // Xóa session booking_data
                Log::info('Đã xóa session vnpay_booking_id và booking_data.');

                return redirect()->route('front.booking.success')
                    ->with('success', 'Thanh toán thành công! Vui lòng chờ nhà hàng xác nhận đơn của bạn.');
            } else {
                // Thanh toán thất bại
                Log::info('Thanh toán thất bại cho booking:', ['booking_id' => $bookingId]);

                // Lưu thông tin thanh toán thất bại
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_amount * 0.2, // Giả sử thanh toán trước 20%
                    'payment_type' => 'deposit',
                    'payment_method' => 'vnpay',
                    'transaction_id' => $request->vnp_TransactionNo,
                    'transaction_ref' => $request->vnp_TxnRef,
                    'status' => 'failed',
                    'payment_time' => now(),
                ]);
                Log::info('Đã lưu thông tin thanh toán thất bại.');

                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled' // Có thể cập nhật thành cancelled hoặc giữ nguyên pending
                ]);
                Log::info('Đã cập nhật trạng thái booking thành failed.');

                // Xóa session
                $request->session()->forget('vnpay_booking_id');
                $request->session()->forget('booking_data'); // Xóa session booking_data
                Log::info('Đã xóa session vnpay_booking_id và booking_data.');

                return redirect()->route('front.booking')
                    ->with('error', 'Thanh toán không thành công. Vui lòng thử lại.');
            }
        } else {
            Log::error('Chữ ký VNPay không hợp lệ.');

            // Chữ ký không hợp lệ
            // Bạn có thể lưu thông tin thanh toán với trạng thái 'failed' hoặc 'invalid'
            Payment::create([
                'booking_id' => $booking->id, // Có thể không có booking_id trong trường hợp này
                'amount' => $request->vnp_Amount, // Số tiền từ VNPay (nếu có)
                'payment_type' => 'deposit',
                'payment_method' => 'vnpay',
                'transaction_ref' => $request->vnp_TxnRef,
                'status' => 'failed', // Hoặc 'invalid'
                'payment_time' => now(),
            ]);
            Log::info('Đã lưu thông tin thanh toán với trạng thái chữ ký không hợp lệ.');

            // Xóa session
            $request->session()->forget('vnpay_booking_id');
            $request->session()->forget('booking_data');
            Log::info('Đã xóa session vnpay_booking_id và booking_data.');

            return redirect()->route('front.booking')
                ->with('error', 'Chữ ký không hợp lệ!');
        }
    }
}