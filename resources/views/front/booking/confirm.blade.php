@extends('layouts.front')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Xác nhận đặt bàn</h3>
                </div>
                <div class="card-body">
                    <!-- Thông tin đặt bàn -->
                     
                    <h5>Thông tin đặt bàn</h5>
                    <table class="table">
                        <tr>
                            <td>Họ tên:</td>
                            <td>{{ $bookingData['name'] }}</td>
                        </tr>
                        <tr>
                            <td>Số điện thoại:</td>
                            <td>{{ $bookingData['phone'] }}</td>
                        </tr>
                        <tr>
                            <td>Ngày giờ:</td>
                            <td>{{ \Carbon\Carbon::parse($bookingData['booking_date'])->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>Số người:</td>
                            <td>{{ $bookingData['number_of_people'] }}</td>
                        </tr>
                    </table>

                    @if($bookingData['booking_type'] === 'with_menu' && isset($bookingData['menu_items']) && is_array($bookingData['menu_items']) && count($bookingData['menu_items']) > 0)
                    <!-- Danh sách món ăn -->
                    <h5 class="mt-4">Món ăn đã chọn</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tên món</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalAmount = 0; @endphp
                            @foreach($bookingData['menu_items'] as $menuId => $item)
                                @if(isset($item['selected']) && $item['selected'] === 'on')
                                    @php
                                        $menu = App\Models\Menu::find($menuId);
                                    @endphp
                                    @if ($menu)
                                        @php
                                            $subtotal = $menu->price * $item['quantity'];
                                            $totalAmount += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $menu->name }}</td>
                                            <td>{{ $item['quantity'] }}</td>
                                            <td>{{ number_format($menu->price) }}đ</td>
                                            <td>{{ number_format($subtotal) }}đ</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="4">Không tìm thấy thông tin món ăn.</td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tổng tiền:</strong></td>
                                <td><strong>{{ number_format($totalAmount) }}đ</strong></td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="3" class="text-end"><strong>Tiền đặt cọc (20%):</strong></td>
                                <td><strong>{{ number_format($totalAmount * 0.2) }}đ</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    @endif

                    <!-- Form xác nhận đặt bàn -->
                    @if(!Auth::check())
                    <form action="{{ route('front.booking.confirm') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Nhân viên của chúng tôi sẽ gọi điện xác nhận đặt bàn của bạn trong thời gian sớm nhất.
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Xác nhận đặt bàn
                        </button>
                    </form>
                    @else
                    <!-- Form xác nhận đặt bàn cho khách đã đăng nhập -->
                    <form action="{{ route('front.booking.confirm') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Sau khi xác nhận đặt bàn, bạn có thể vào phần "Lịch sử đặt bàn" để xem chi tiết.
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Xác nhận đặt bàn
                        </button>
                        <a href="{{ route('front.booking') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection