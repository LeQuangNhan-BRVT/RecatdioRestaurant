@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Sửa đơn đặt bàn #{{ $booking->id }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">Trở về</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Khách hàng:</label>
                                <p class="form-control-static">{{ $booking->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label>Số điện thoại:</label>
                                <p class="form-control-static">{{ $booking->phone }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Ngày đặt:</label>
                                <p class="form-control-static">{{ $booking->booking_date->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <label for="number_of_people">Số người:</label>
                                <input type="number" name="number_of_people" 
                                       class="form-control" 
                                       value="{{ old('number_of_people', $booking->number_of_people) }}"
                                       min="1">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="special_request">Ghi chú đặc biệt:</label>
                        <textarea name="special_request" class="form-control" rows="3">{{ old('special_request', $booking->special_request) }}</textarea>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Danh sách món ăn</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="text" 
                                       id="menuSearch" 
                                       class="form-control" 
                                       placeholder="Tìm kiếm món ăn...">
                            </div>

                            <ul class="nav nav-tabs" id="menuTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" 
                                       data-bs-toggle="tab" 
                                       href="#all-menu" 
                                       role="tab">Tất cả</a>
                                </li>
                                @foreach($categories as $category)
                                <li class="nav-item">
                                    <a class="nav-link" 
                                       data-bs-toggle="tab" 
                                       href="#category-{{ $category->id }}" 
                                       role="tab">{{ $category->name }}</a>
                                </li>
                                @endforeach
                            </ul>

                            <div class="tab-content mt-3">
                                <div class="tab-pane fade show active" id="all-menu" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered menu-table">
                                            <thead>
                                                <tr>
                                                    <th>Món ăn</th>
                                                    <th width="150">Số lượng</th>
                                                    <th width="150">Đơn giá</th>
                                                    <th width="150">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($menus as $menu)
                                                <tr class="menu-item" data-name="{{ strtolower($menu->name) }}">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset($menu->image) }}" 
                                                                 alt="{{ $menu->name }}" 
                                                                 class="me-2"
                                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                                            <div>
                                                                <strong>{{ $menu->name }}</strong>
                                                                <br>
                                                                <small class="text-muted">{{ $menu->category->name }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               name="menus[{{ $menu->id }}]" 
                                                               class="form-control menu-quantity" 
                                                               value="{{ old('menus.' . $menu->id, $booking->bookingMenus->where('menu_id', $menu->id)->first()->quantity ?? 0) }}"
                                                               min="0"
                                                               data-price="{{ $menu->price }}">
                                                    </td>
                                                    <td class="text-end">
                                                        {{ number_format($menu->price) }}đ
                                                    </td>
                                                    <td class="text-end subtotal">
                                                        0đ
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                                    <td class="text-end" id="total">0đ</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                @foreach($categories as $category)
                                <div class="tab-pane fade" id="category-{{ $category->id }}" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered menu-table">
                                            <thead>
                                                <tr>
                                                    <th>Món ăn</th>
                                                    <th width="150">Số lượng</th>
                                                    <th width="150">Đơn giá</th>
                                                    <th width="150">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($menus->where('category_code', $category->category_code) as $menu)
                                                <tr class="menu-item" data-name="{{ strtolower($menu->name) }}">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset($menu->image) }}" 
                                                                 alt="{{ $menu->name }}" 
                                                                 class="me-2"
                                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                                            <div>
                                                                <strong>{{ $menu->name }}</strong>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               name="menus[{{ $menu->id }}]" 
                                                               class="form-control menu-quantity" 
                                                               value="{{ old('menus.' . $menu->id, $booking->bookingMenus->where('menu_id', $menu->id)->first()->quantity ?? 0) }}"
                                                               min="0"
                                                               max="10"
                                                               data-price="{{ $menu->price }}">
                                                    </td>
                                                    <td class="text-end">
                                                        {{ number_format($menu->price) }}đ
                                                    </td>
                                                    <td class="text-end subtotal">
                                                        0đ
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
$(document).ready(function() {
    function updateSubtotal() {
        let total = 0;
        $('.menu-quantity:visible').each(function() {
            const quantity = parseInt($(this).val()) || 0;
            const price = parseFloat($(this).data('price'));
            const subtotal = quantity * price;
            
            $(this).closest('tr').find('.subtotal').text(
                new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ'
            );
            
            total += subtotal;
        });
        
        $('#total').text(new Intl.NumberFormat('vi-VN').format(total) + 'đ');
    }

    $('#menuSearch').on('input', function() {
        const searchText = $(this).val().toLowerCase();
        $('.menu-item').each(function() {
            const menuName = $(this).data('name');
            $(this).toggle(menuName.includes(searchText));
        });
    });

    $('.menu-quantity').on('input', updateSubtotal);
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', updateSubtotal);
    updateSubtotal();
});
</script>
@endpush
@endsection 