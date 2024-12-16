@extends('layouts.front')

@section('title', 'Lịch sử đặt bàn')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Lịch sử đặt bàn</h2>
            
            @if(!isset($bookings) || $bookings->isEmpty())
                <div class="alert alert-info">
                    <p class="mb-0">Bạn chưa có đơn đặt bàn nào.</p>
                    <p class="mb-0 mt-2">
                        <a href="{{ route('front.booking') }}" class="alert-link">Đặt bàn ngay</a> để trải nghiệm dịch vụ của chúng tôi!
                    </p>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Ngày đặt</th>
                                        <th>Giờ đặt</th>
                                        <th>Số người</th>
                                        <th>Trạng thái</th>
                                        <th>Thanh toán</th>
                                        <th>Ghi chú</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>#{{ $booking->id }}</td>
                                        <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                                        <td>{{ $booking->booking_date->format('H:i') }}</td>
                                        <td>{{ $booking->number_of_people }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status_color }}">
                                                @php
                                                    $statusText = [
                                                        'pending' => 'Đang chờ xác nhận',
                                                        'confirmed' => 'Đã xác nhận',
                                                        'cancelled' => 'Đã hủy',
                                                        'completed' => 'Hoàn thành'
                                                    ];
                                                @endphp
                                                {{ $statusText[$booking->status] ?? 'Không xác định' }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($booking->payment_status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                                    @break
                                                @case('processing')
                                                    <span class="badge bg-info">Đang xử lý</span>
                                                    @break
                                                @case('paid')
                                                    <span class="badge bg-success">Đã thanh toán</span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge bg-danger">Thanh toán thất bại</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">Không xác định</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $booking->special_request ?: 'Không có' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-info btn-sm btn-view-detail" 
                                                        data-id="{{ $booking->id }}">
                                                    <i class="fas fa-eye"></i> Chi tiết
                                                </button>

                                                @if($booking->status === 'pending' && $booking->payment_status === 'pending')
                                                    <form action="{{ route('bookings.cancel', $booking->id) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-danger btn-sm cancel-booking">
                                                            <i class="fas fa-times"></i> Hủy đơn
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($bookings->hasPages())
                            <div class="mt-4">
                                {{ $bookings->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal container -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra SweetAlert
    console.log('SweetAlert exists:', typeof Swal !== 'undefined');

    $(document).on('click', '.cancel-booking', function(e) {
        console.log('Nút hủy được click');
        e.preventDefault();
        console.log('Đã prevent default');
        
        const form = $(this).closest('form');
        console.log('Form được tìm thấy:', form.length > 0);
        console.log('Action của form:', form.attr('action'));
        
        try {
            console.log('Bắt đầu hiển thị SweetAlert');
            Swal.fire({
                title: 'Xác nhận hủy đơn',
                text: "Bạn có chắc chắn muốn hủy đơn này?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fea116',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Không'
            }).then((result) => {
                console.log('SweetAlert callback triggered');
                console.log('Kết quả từ SweetAlert:', result);
                if (result.isConfirmed) {
                    console.log('Người dùng đã xác nhận, submit form');
                    form.submit();
                } else {
                    console.log('Người dùng đã hủy thao tác');
                }
            }).catch(error => {
                console.error('SweetAlert error:', error);
            });
        } catch (error) {
            console.error('Error showing SweetAlert:', error);
        }
    });

    // Xử lý khi click nút Chi tiết
    $(document).on('click', '.btn-view-detail', function(e) {
        e.preventDefault();
        const bookingId = $(this).data('id');
        console.log('Clicked booking ID:', bookingId); // Debug log

        // Hiển thị loading
        $('#bookingDetailModal .modal-content').html('<div class="modal-body text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#bookingDetailModal').modal('show');

        // Gọi API lấy chi tiết
        $.ajax({
            url: `/bookings/${bookingId}/detail`,
            method: 'GET',
            success: function(response) {
                console.log('API response:', response); // Debug log
                if (response.status) {
                    $('#bookingDetailModal .modal-content').html(response.data.html);
                } else {
                    $('#bookingDetailModal .modal-content').html(`
                        <div class="modal-body">
                            <div class="alert alert-danger">
                                ${response.message || 'Có lỗi xảy ra khi tải dữ liệu'}
                            </div>
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('API error:', error); // Debug log
                $('#bookingDetailModal .modal-content').html(`
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            Không thể tải thông tin đơn đặt bàn
                        </div>
                    </div>
                `);
            }
        });
    });
});

// Hiển thị thông báo thành công nếu có
var successMessage = '{!! session("success") !!}';
console.log('Success message:', successMessage);
if (successMessage) {
    Swal.fire({
        title: 'Thành công!',
        text: successMessage,
        icon: 'success',
        confirmButtonColor: '#fea116',
        confirmButtonText: 'Đồng ý'
    });
}
</script>
@endpush