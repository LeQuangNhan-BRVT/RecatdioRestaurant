@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Đơn đặt bàn hôm nay</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Khách hàng</th>
                                    <th>Số điện thoại</th>
                                    <th>Thời gian đặt</th>
                                    <th>Số người</th>
                                    <th>Trạng thái</th>
                                    <th>Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayBookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->name }}</td>
                                    <td>{{ $booking->phone }}</td>
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
                                    <td>{{ number_format($booking->total_amount) }}đ</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có đơn đặt bàn nào hôm nay</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection