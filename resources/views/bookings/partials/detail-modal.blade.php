<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Chi tiết đơn đặt bàn #{{ $booking->id }}</h5>
        <button type="button" class="close" id="closeModalBtn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <!-- Thông tin đặt bàn -->
        <h6>Thông tin đặt bàn</h6>
        <table class="table">
            <!-- ... các thông tin cơ bản ... -->
        </table>

        <!-- Danh sách món đã đặt -->
        @if($booking->bookingMenus->count() > 0)
        <div class="mt-4">
            <h6>Món ăn đã đặt</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên món</th>
                            <th class="text-center" width="100">Số lượng</th>
                            <th class="text-end" width="150">Đơn giá</th>
                            <th class="text-end" width="150">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->bookingMenus as $bookingMenu)
                        <tr>
                            <td>{{ $bookingMenu->menu->name }}</td>
                            <td class="text-center">{{ $bookingMenu->quantity }}</td>
                            <td class="text-end">{{ number_format($bookingMenu->price) }}đ</td>
                            <td class="text-end">{{ number_format($bookingMenu->subtotal) }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Tổng tiền:</strong></td>
                            <td class="text-end"><strong>{{ number_format($booking->total_amount) }}đ</strong></td>
                        </tr>
                        <tr class="table-info">
                            <td colspan="3" class="text-end"><strong>Tiền đặt cọc (20%):</strong></td>
                            <td class="text-end"><strong>{{ number_format($booking->total_amount * 0.2) }}đ</strong></td>
                        </tr>
                        @if($booking->payment_status === 'paid')
                        <tr class="table-success">
                            <td colspan="3" class="text-end"><strong>Đã thanh toán cọc:</strong></td>
                            <td class="text-end"><strong>{{ number_format($booking->total_amount * 0.2) }}đ</strong></td>
                        </tr>
                        <tr class="table-warning">
                            <td colspan="3" class="text-end"><strong>Còn lại cần thanh toán:</strong></td>
                            <td class="text-end"><strong>{{ number_format($booking->total_amount * 0.8) }}đ</strong></td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Phần thanh toán -->
        @if($booking->status === 'confirmed' && $booking->payment_status === 'pending')
        <div class="mt-4">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Đơn đặt bàn của bạn đã được xác nhận. Vui lòng đặt cọc 20% để hoàn tất đặt bàn.
            </div>
            <form action="{{ route('front.booking.process-payment') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fab fa-cc-visa me-2"></i>
                    Thanh toán cọc {{ number_format($booking->total_amount * 0.2) }}đ qua VNPay
                </button>
            </form>
        </div>
        @elseif($booking->status === 'pending')
        <div class="alert alert-warning mt-4">
            <i class="fas fa-clock me-2"></i>
            Vui lòng chờ nhà hàng xác nhận đơn đặt bàn của bạn.
        </div>
        @elseif($booking->payment_status === 'paid')
        <div class="alert alert-success mt-4">
            <i class="fas fa-check-circle me-2"></i>
            Bạn đã thanh toán tiền cọc thành công!
        </div>
        @elseif($booking->payment_status === 'completed')
        <div class="alert alert-success mt-4">
            <i class="fas fa-check-circle me-2"></i>
            Bạn đã thanh toán đầy đủ!
        </div>
        @elseif($booking->status === 'cancelled')
        <div class="alert alert-danger mt-4">
            <i class="fas fa-times-circle me-2"></i>
            Đơn đặt bàn đã bị hủy.
        </div>
        @endif
        @endif

        <!-- Thông tin thêm -->
        @if($booking->special_request)
        <div class="mt-3">
            <h6>Yêu cầu đặc biệt:</h6>
            <p class="text-muted">{{ $booking->special_request }}</p>
        </div>
        @endif

        <!-- Trạng thái đơn -->
        <div class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <strong>Trạng thái đơn:</strong>
                    @if($booking->status === 'pending')
                        <span class="badge badge-warning">Chờ xác nhận</span>
                    @elseif($booking->status === 'confirmed')
                        <span class="badge badge-success">Đã xác nhận</span>
                    @elseif($booking->status === 'cancelled')
                        <span class="badge badge-danger">Đã hủy</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <strong>Trạng thái thanh toán:</strong>
                    @if($booking->payment_status === 'pending')
                        <span class="badge badge-warning">Chưa thanh toán</span>
                    @elseif($booking->payment_status === 'processing')
                        <span class="badge badge-info">Đang xử lý</span>
                    @elseif($booking->payment_status === 'paid')
                        <span class="badge badge-success">Đã đặt cọc</span>
                    @elseif($booking->payment_status === 'completed')
                        <span class="badge badge-success">Đã thanh toán đủ</span>
                    @elseif($booking->payment_status === 'failed')
                        <span class="badge badge-danger">Thanh toán thất bại</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="closeModalFooterBtn" >Đóng</button>
    </div>
</div> 
