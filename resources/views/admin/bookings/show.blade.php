@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chi tiết đặt bàn #{{ $booking->id }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">Trở về</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin đặt bàn</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th style="width:200px">Tên khách hàng:</th>
                                <td>{{ $booking->name }}</td>
                            </tr>
                            <tr>
                                <th>Số điện thoại:</th>
                                <td>{{ $booking->phone }}</td>
                            </tr>
                            @if($booking->user_id)
                            <tr>
                                <th>Email:</th>
                                <td>{{ $booking->user->email }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Ngày đặt bàn:</th>
                                <td>{{ $booking->booking_date->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Số người:</th>
                                <td>{{ $booking->number_of_people }}</td>
                            </tr>
                            <tr>
                                <th>Yêu cầu đặc biệt:</th>
                                <td>{{ $booking->special_request ?: 'Không có' }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    <select class="form-control booking-status" data-id="{{ $booking->id }}">
                                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin thanh toán</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th style="width:200px">Tổng tiền:</th>
                                <td>{{ number_format($booking->total_amount) }}đ</td>
                            </tr>
                            <tr>
                                <th>Tiền đặt cọc (20%):</th>
                                <td>{{ number_format($booking->total_amount * 0.2) }}đ</td>
                            </tr>
                            @if($booking->payment_status === 'paid')
                            <tr>
                                <th>Đã thanh toán cọc:</th>
                                <td class="text-success">
                                    <strong>{{ number_format($booking->total_amount * 0.2) }}đ</strong>
                                    <i class="fas fa-check-circle"></i>
                                </td>
                            </tr>
                            <tr>
                                <th>Còn lại cần thanh toán:</th>
                                <td class="text-warning">
                                    <strong>{{ number_format($booking->total_amount * 0.8) }}đ</strong>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>Trạng thái thanh toán:</th>
                                <td>
                                    @if($booking->payment_status === 'pending')
                                    <span class="badge badge-warning">Chưa thanh toán</span>
                                    @elseif($booking->payment_status === 'processing')
                                    <span class="badge badge-info">Đang xử lý</span>
                                    @elseif($booking->payment_status === 'paid')
                                    <span class="badge badge-success">Đã đặt cọc</span>
                                    @elseif($booking->payment_status === 'fully_paid')
                                    <span class="badge badge-success">Đã thanh toán đầy đủ</span>
                                    @elseif($booking->payment_status === 'failed')
                                    <span class="badge badge-danger">Thanh toán thất bại</span>
                                    @else
                                    <span class="badge badge-secondary">Không xác định</span>
                                    @endif
                                </td>
                            </tr>
                            @if($booking->payment_status === 'paid' && $booking->status === 'confirmed')
                            <tr>
                                <th>Thời gian thanh toán:</th>
                                <td>{{ $booking->payment_time ? $booking->payment_time->format('d/m/Y H:i:s') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Mã giao dịch:</th>
                                <td>{{ $booking->transaction_id ?: 'N/A' }}</td>
                            </tr>
                            @endif
                            @if($booking->payment_status === 'paid' && $booking->status === 'confirmed')
                            <tr>
                                <td colspan="2">
                                    <div class="text-center mt-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button type="button"
                                                    class="btn btn-primary"
                                                    data-toggle="modal"
                                                    data-target="#qrModal">
                                                    <i class="fas fa-qrcode"></i>
                                                    Hiển thị mã QR thanh toán ({{ number_format($booking->total_amount * 0.8) }}đ)
                                                </button>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button"
                                                    class="btn btn-success confirm-full-payment-btn"
                                                    data-booking="{{ $booking->id }}">
                                                    <i class="fas fa-check-double"></i>
                                                    Xác nhận đã thanh toán đầy đủ
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </table>

                        @if($booking->payment_status === 'paid' && $booking->status === 'confirmed')


                        <!-- Modal xác nhận thanh toán -->
                        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Xác nhận thanh toán</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Xác nhận thanh toán số tiền còn lại:</p>
                                        <table class="table">
                                            <tr>
                                                <th>Tổng hóa đơn:</th>
                                                <td class="text-end">{{ number_format($booking->total_amount) }}đ</td>
                                            </tr>
                                            <tr>
                                                <th>Đã đặt cọc:</th>
                                                <td class="text-end text-success">
                                                    {{ number_format($booking->total_amount * 0.2) }}đ
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Số tiền cần thanh toán:</th>
                                                <td class="text-end text-danger">
                                                    <strong>{{ number_format($booking->total_amount * 0.8) }}đ</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                        <button type="button" class="btn btn-success confirm-payment" data-id="{{ $booking->id }}">
                                            <i class="fas fa-check"></i> Xác nhận đã thanh toán
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($booking->bookingMenus->count() > 0)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách món ăn</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Món ăn</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->bookingMenus as $item)
                                <tr>
                                    <td>{{ $item->menu->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price) }}đ</td>
                                    <td>{{ number_format($item->subtotal) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Tổng cộng:</th>
                                    <th>{{ number_format($booking->total_amount) }}đ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Modal QR Code -->
<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mã QR thanh toán</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('admin-assets/img/credit/qrcode.png') }}"
                    alt="QR Code"
                    class="img-fluid">
                <div class="mt-2">
                    <small class="text-muted">Khách hàng có thể quét mã QR này để thanh toán số tiền còn lại</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customjs')
<script>
    $(document).ready(function() {
        $('.booking-status').change(function() {
            var bookingId = $(this).data('id');
            var status = $(this).val();
            var statusSelect = $(this);
            var originalStatus = statusSelect.data('original-status') || status;

            Swal.fire({
                title: 'Xác nhận thay đổi',
                text: "Bạn có chắc chắn muốn cập nhật trạng thái đơn này?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hiển thị loading
                    Swal.fire({
                        title: 'Đang xử lý...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '/admin/bookings/' + bookingId + '/status',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: status
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: 'Cập nhật trạng thái thành công',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    // Cập nhật trạng thái ban đầu nếu thành công
                                    statusSelect.data('original-status', status);
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Lỗi!',
                                    text: response.message || 'Có lỗi xảy ra',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6'
                                });
                                // Khôi phục trạng thái ban đầu
                                statusSelect.val(originalStatus);
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra khi cập nhật trạng thái',
                                icon: 'error',
                                confirmButtonColor: '#3085d6'
                            });
                            // Khôi phục trạng thái ban đầu
                            statusSelect.val(originalStatus);
                        }
                    });
                } else {
                    // Khôi phục trạng thái ban đầu
                    statusSelect.val(originalStatus);
                }
            });
        });

        $('.confirm-payment').click(function() {
            var bookingId = $(this).data('id');

            Swal.fire({
                title: 'Xác nhận thanh toán',
                text: "Bạn có chắc chắn đã nhận thanh toán đầy đủ cho đơn đặt bàn này?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hiển thị loading
                    Swal.fire({
                        title: 'Đang xử lý...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '/admin/bookings/' + bookingId + '/complete-payment',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: 'Thanh toán thành công!',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    $('#paymentModal').modal('hide');
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Lỗi!',
                                    text: response.message || 'Có lỗi xảy ra',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra khi xử lý thanh toán',
                                icon: 'error',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                }
            });
        });

        // Xử lý xác nhận thanh toán đầy đủ
        $('.confirm-full-payment-btn').click(function() {
            var bookingId = $(this).data('booking');

            Swal.fire({
                title: 'Xác nhận thanh toán đầy đủ',
                text: "Bạn có chắc chắn đã nhận đủ thanh toán cho đơn đặt bàn này?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hiển thị loading
                    Swal.fire({
                        title: 'Đang xử lý...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '{{ route("admin.bookings.confirmFullPayment", $booking->id) }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: 'Đã xác nhận thanh toán đầy đủ!',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Lỗi!',
                                    text: response.message || 'Có lỗi xảy ra',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra khi xử lý yêu cầu',
                                icon: 'error',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection