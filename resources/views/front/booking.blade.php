@extends('layouts.front')

@section('content')
<div class="container-xxl py-5 px-0 wow fadeInUp" data-wow-delay="0.1s">
    <div class="row g-0">
        <div class="col-md-12 bg-dark d-flex align-items-center">
            <div class="p-5 wow fadeInUp" data-wow-delay="0.2s">
                <h5 class="section-title ff-secondary text-start text-primary fw-normal">Đặt Bàn</h5>
                <h1 class="text-white mb-4">Đặt Bàn Trực Tuyến</h1>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Thêm tabs cho 2 loại đặt bàn -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#only_table" type="button">
                            Chỉ đặt bàn
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#with-menu" type="button">
                            Đặt bàn và chọn món
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Tab chỉ đặt bàn -->
                    <div class="tab-pane fade show active" id="only_table">
                        <form method="POST" action="{{ route('front.booking.store') }}">
                            @csrf
                            <input type="hidden" name="booking_type" value="only_table">
                            @include('front.booking._form')

                            <div class="col-12 mt-4">
                                <button class="btn btn-primary w-100 py-3" type="submit">Đặt Bàn Ngay</button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab đặt bàn và chọn món -->
                    <div class="tab-pane fade" id="with-menu">
                        <form method="POST" action="{{ route('front.booking.store') }}">
                            @csrf
                            <input type="hidden" name="booking_type" value="with_menu">
                            @include('front.booking._form')

                            <!-- Phần chọn món -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">Chọn món ăn</h5>
                                </div>
                                
                                <!-- Menu tabs -->
                                <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
                                    <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                                        @foreach($categories as $key => $category)
                                        <li class="nav-item">
                                            <a class="d-flex align-items-center text-start mx-3 @if($key === 0) ms-0 active @endif" 
                                               data-bs-toggle="pill" 
                                               href="#menu-tab-{{ $category->category_code }}">
                                                <i class="fa fa-utensils fa-2x text-primary"></i>
                                                <div class="ps-3">
                                                    <small class="text-body">{{ $category->description }}</small>
                                                    <h6 class="mt-n1 mb-0 text-white">{{ $category->name }}</h6>
                                                </div>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>

                                    <div class="tab-content">
                                        @foreach($categories as $key => $category)
                                        <div id="menu-tab-{{ $category->category_code }}" 
                                             class="tab-pane fade show p-0 @if($key === 0) active @endif">
                                            <div class="row g-4">
                                                @foreach($category->menu as $item)
                                                    @if($item->status == 1)
                                                    <div class="col-lg-6">
                                                        <div class="menu-item-container">
                                                            <div class="d-flex align-items-center">
                                                                <div class="menu-img-container">
                                                                    <img class="menu-img" 
                                                                         src="{{ URL::to('/'.$item->image) }}"
                                                                         onerror="this.onerror=null; this.src='/uploads/menu/1732596610_cook.jpg';"
                                                                         alt="{{ $item->name }}">
                                                                </div>
                                                                <div class="w-100 d-flex flex-column text-start ps-4">
                                                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                                                        <span class="text-white">{{ $item->name }}</span>
                                                                        <span class="price-tag">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span>
                                                                    </h5>
                                                                    <small class="fst-italic text-white-50">{{ $item->description }}</small>
                                                                    <div class="d-flex align-items-center mt-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input menu-item" type="checkbox" 
                                                                                name="menu_items[{{ $item->id }}][selected]" 
                                                                                id="menu{{ $item->id }}"
                                                                                data-price="{{ $item->price }}">
                                                                            <label class="form-check-label text-white" for="menu{{ $item->id }}">
                                                                                Chọn món
                                                                            </label>
                                                                        </div>
                                                                        <div class="ms-auto d-flex align-items-center">
                                                                            <button type="button" class="btn btn-sm btn-outline-primary me-2 decrease-quantity" disabled>-</button>
                                                                            <input type="number" class="form-control form-control-sm quantity-input text-center" 
                                                                                min="1" 
                                                                                max="10"
                                                                                name="menu_items[{{ $item->id }}][quantity]" 
                                                                                value="1"
                                                                                disabled
                                                                                style="width: 60px">
                                                                            <button type="button" class="btn btn-sm btn-outline-primary ms-2 increase-quantity" disabled>+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Hiển thị tổng tiền -->
                                <div class="col-12">
                                    <div class="total-amount-container d-flex justify-content-between text-white">
                                        <h5>Tổng tiền:</h5>
                                        <h5 id="total-amount" class="price-tag">0đ</h5>
                                        <input type="hidden" name="total_amount" id="total-amount-input" value="0">
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Đặt Bàn Ngay</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    console.log('jQuery ready');
    
    $('.menu-item').each(function() {
        const $item = $(this);
        const $container = $item.closest('.d-flex');
        const $quantityInput = $container.find('.quantity-input');
        const $decreaseBtn = $container.find('.decrease-quantity');
        const $increaseBtn = $container.find('.increase-quantity');

        // Xử lý khi checkbox thay đổi
        $item.on('change', function() {
            const isChecked = $(this).is(':checked');
            $quantityInput.prop('disabled', !isChecked);
            $decreaseBtn.prop('disabled', !isChecked);
            $increaseBtn.prop('disabled', !isChecked);
            updateTotal();
        });

        // Xử lý nút giảm số lượng
        $decreaseBtn.on('click', function() {
            const currentValue = parseInt($quantityInput.val());
            if (currentValue > 1) {
                $quantityInput.val(currentValue - 1);
                updateTotal();
            }
        });

        // Xử lý nút tăng số lượng
        $increaseBtn.on('click', function() {
            const currentValue = parseInt($quantityInput.val());
            if (currentValue < 10) {
                $quantityInput.val(currentValue + 1);
                updateTotal();
            }
        });

        // Xử lý khi nhập trực tiếp số lượng
        $quantityInput.on('input', function() {
            let value = parseInt($(this).val());
            if (isNaN(value) || value < 1) {
                value = 1;
            } else if (value > 10) {
                value = 10;
            }
            $(this).val(value);
            updateTotal();
        });
    });

    function updateTotal() {
        let total = 0;
        $('.menu-item:checked').each(function() {
            const price = parseFloat($(this).data('price'));
            const quantity = parseInt($(this).closest('.d-flex').find('.quantity-input').val());
            total += price * quantity;
        });
        
        $('#total-amount').text(new Intl.NumberFormat('vi-VN').format(total) + 'đ');
        $('#total-amount-input').val(total);
    }
});
</script>
@endpush
@endsection