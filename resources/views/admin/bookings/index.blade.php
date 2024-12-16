@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Danh sách đặt bàn</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <!-- Bộ lọc -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('admin.bookings.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <input type="text" name="search" class="form-control"
                                placeholder="Tìm theo tên, SĐT, số người..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="payment_status" class="form-control">
                                <option value="">Tất cả TT thanh toán</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                <option value="processing" {{ request('payment_status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã đặt cọc</option>
                                <option value="fully_paid" {{ request('payment_status') == 'fully_paid' ? 'selected' : '' }}>Đã thanh toán đầy đủ</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="submit" class="btn btn-primary">Lọc</button>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Đặt lại</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    ID {!! getSortIcon('id') !!}
                                </a>
                            </th>
                            <th>Khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'booking_date', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    Ngày đặt {!! getSortIcon('booking_date') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'number_of_people', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    Số người {!! getSortIcon('number_of_people') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'booking_type', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    Loại đặt bàn {!! getSortIcon('booking_type') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_amount', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    Tổng tiền {!! getSortIcon('total_amount') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    Trạng thái {!! getSortIcon('status') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'payment_status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                    Thanh toán {!! getSortIcon('payment_status') !!}
                                </a>
                            </th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>
                                {{ $booking->name }}
                                @if($booking->user_id)
                                <br><small class="text-muted">Tài khoản: {{ $booking->user->email }}</small>
                                @endif
                            </td>
                            <td>{{ $booking->phone }}</td>
                            <td>{{ $booking->booking_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $booking->number_of_people }}</td>
                            <td>
                                @if($booking->bookingMenus->count() > 0)
                                <span class="badge badge-success">Đặt kèm món</span>
                                @else
                                <span class="badge badge-info">Chỉ đặt bàn</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->bookingMenus->count() > 0)
                                {{ number_format($booking->total_amount) }}đ
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                <select class="form-select update-status"
                                    data-booking-id="{{ $booking->id }}"
                                    data-original-status="{{ $booking->status }}">
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </td>
                            <td>
                                @switch($booking->payment_status)
                                @case('pending')
                                <span class="badge badge-warning">Chờ thanh toán</span>
                                @break
                                @case('processing')
                                <span class="badge badge-info">Đang xử lý</span>
                                @break
                                @case('paid')
                                <span class="badge badge-success">Đã đặt cọc</span>
                                @break
                                @case('fully_paid')
                                <span class="badge badge-success">Đã thanh toán đầy đủ</span>
                                @break
                                @case('failed')
                                <span class="badge badge-danger">Thanh toán thất bại</span>
                                @break
                                @default
                                <span class="badge badge-secondary">Không xác định</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group action-buttons">
                                    <a href="{{ route('admin.bookings.edit', $booking->id) }}"
                                        class="btn btn-info btn-sm mx-1"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                        class="btn btn-primary btn-sm mx-1"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <button type="button"
                                        class="btn btn-danger btn-sm delete-booking mx-1"
                                        data-id="{{ $booking->id }}"
                                        title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">Không có đơn đặt bàn nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customjs')
<script>
    $(document).ready(function() {
        // Xử lý cập nhật trạng thái đặt bàn
        $('.update-status').on('change', function() {
            const bookingId = $(this).data('booking-id');
            const newStatus = $(this).val();
            const statusSelect = $(this);

            try {
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

                        // Gửi request cập nhật
                        $.ajax({
                            url: `/admin/bookings/${bookingId}/status`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: newStatus
                            },
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: 'Thành công!',
                                        text: 'Cập nhật trạng thái thành công',
                                        icon: 'success',
                                        confirmButtonColor: '#3085d6'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Lỗi!',
                                        text: response.message || 'Có lỗi xảy ra',
                                        icon: 'error',
                                        confirmButtonColor: '#3085d6'
                                    });
                                    statusSelect.val(statusSelect.data('original-status'));
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Lỗi!',
                                    text: 'Có lỗi xảy ra khi cập nhật trạng thái',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6'
                                });
                                statusSelect.val(statusSelect.data('original-status'));
                            }
                        });
                    } else {
                        statusSelect.val(statusSelect.data('original-status'));
                    }
                });
            } catch (error) {
                console.error('Error showing SweetAlert:', error);
            }
        });

        // Xóa đặt bàn
        $('.delete-booking').click(function() {
            const bookingId = $(this).data('id');
            
            Swal.fire({
                title: 'Xác nhận xóa',
                text: "Bạn có chắc chắn muốn xóa đơn đặt bàn này?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/bookings/' + bookingId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: 'Xóa đơn đặt bàn thành công',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        }
                    });
                }
            });
        });
    });

    function getSortIcon(field) {
        const currentSort = '{{ request('sort') }}';
        const currentDirection = '{{ request('direction') }}';

        if (currentSort !== field) return '';

        return currentDirection === 'asc' ?
            '<i class="fas fa-sort-up ml-1"></i>' :
            '<i class="fas fa-sort-down ml-1"></i>';
    }
</script>
@endsection