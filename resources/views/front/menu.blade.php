@extends('layouts.front')

@section('title', 'Thực đơn')

@section('hero-title', 'Thực đơn')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('front.home') }}">Trang chủ</a></li>
<li class="breadcrumb-item text-white active" aria-current="page">Thực đơn</li>
@endsection

@section('content')
<!-- Menu Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Thực đơn</h5>
            <h1 class="mb-5">Các món ăn phổ biến</h1>
        </div>
        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
            <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                @foreach($categories as $key => $category)
                <li class="nav-item">
                    <a class="d-flex align-items-center text-start mx-3 @if($key === 0) ms-0 active @endif" 
                       data-bs-toggle="pill" 
                       href="#tab-{{ $category->category_code }}">
                        <i class="fa fa-utensils fa-2x text-primary"></i>
                        <div class="ps-3">
                            <small class="text-body">{{ $category->description }}</small>
                            <h6 class="mt-n1 mb-0">{{ $category->name }}</h6>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($categories as $key => $category)
                <div id="tab-{{ $category->category_code }}" 
                     class="tab-pane fade show p-0 @if($key === 0) active @endif">
                    <div class="row g-4">
                        @foreach($category->menu->where('status', 1)->sortBy('position') as $item)
                        <div class="col-lg-6">
                            <div class="menu-item d-flex align-items-center">
                                <div class="menu-img-container">
                                    <a href="{{ route('front.menu.detail', $item->slug) }}" class="text-decoration-none">
                                        <img class="flex-shrink-0 img-fluid rounded menu-img zoom-img" 
                                             src="{{ URL::to('/'.$item->image) }}"
                                             onerror="this.onerror=null; this.src='/uploads/menu/1732596610_cook.jpg';"
                                             alt="{{ $item->name }}">
                                    </a>
                                </div>
                                <div class="w-100 d-flex flex-column text-start ps-4">
                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                        <a href="{{ route('front.menu.detail', $item->slug) }}" class="text-dark text-decoration-none">
                                            <span>{{ $item->name }}</span>
                                        </a>
                                        <span class="text-primary">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span>
                                    </h5>
                                    <small class="fst-italic">{{ Str::limit($item->description, 100) }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Menu End -->

<!-- Facts Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-utensils text-primary mb-4"></i>
                        <h5>Món ăn đa dạng</h5>
                        <p>Nhiều món ăn phong phú từ Á đến Âu</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-user-tie text-primary mb-4"></i>
                        <h5>Đầu bếp chuyên nghiệp</h5>
                        <p>Đội ngũ đầu bếp giàu kinh nghiệm</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-cart-plus text-primary mb-4"></i>
                        <h5>Đặt món nhanh chóng</h5>
                        <p>Quy trình đặt món đơn giản, nhanh gọn</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-headset text-primary mb-4"></i>
                        <h5>Phục vụ 24/7</h5>
                        <p>Sẵn sàng phục vụ mọi lúc mọi nơi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Facts End -->
@endsection