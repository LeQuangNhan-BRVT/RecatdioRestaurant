@extends('layouts.front')

@section('title', $menu->name)

@section('hero-title', $menu->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('front.home') }}">Trang chủ</a></li>
<li class="breadcrumb-item"><a href="{{ route('front.menu') }}">Thực đơn</a></li>
<li class="breadcrumb-item text-white active" aria-current="page">{{ $menu->name }}</li>
@endsection

@section('content')
<!-- Menu Detail Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="wow fadeInUp" data-wow-delay="0.1s">
                    <div class="menu-detail-img">
                        <img class="img-fluid rounded zoom-img" src="{{ URL::to('/'.$menu->image) }}" 
                             onerror="this.onerror=null; this.src='/uploads/menu/1732596610_cook.jpg';"
                             alt="{{ $menu->name }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="wow fadeInUp" data-wow-delay="0.3s">
                    <h1 class="mb-4">{{ $menu->name }}</h1>
                    <div class="d-flex align-items-center mb-4">
                        <div class="border-end px-4">
                            <h6 class="text-primary mb-2">Danh mục</h6>
                            <h5 class="mb-0">{{ $menu->category->name }}</h5>
                        </div>
                        <div class="px-4">
                            <h6 class="text-primary mb-2">Giá</h6>
                            <h5 class="text-danger mb-0">{{ number_format($menu->price, 0, ',', '.') }} VNĐ</h5>
                        </div>
                    </div>
                    <div class="menu-description mt-4">
                        <h6 class="text-primary mb-3">Mô tả món ăn</h6>
                        <div class="p-4 bg-light rounded">
                            <p class="mb-0 fst-italic">{{ $menu->description }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href= {{route('front.booking')}} class="btn btn-primary py-3 px-5">Đặt bàn</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Menu Detail End -->

<!-- Suggested Menu Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Gợi ý</h5>
            <h1 class="mb-5">Các món ăn khác</h1>
        </div>
        <div class="row g-4">
            @foreach($suggestedMenus as $item)
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="menu-item rounded">
                    <div class="position-relative overflow-hidden suggested-menu-img">
                        <img class="img-fluid w-100" src="{{ URL::to('/'.$item->image) }}"
                             onerror="this.onerror=null; this.src='/uploads/menu/1732596610_cook.jpg';"
                             alt="{{ $item->name }}">
                    </div>
                    <div class="p-4">
                        <h5>
                            <a href="{{ route('front.menu.detail', $item->slug) }}" class="text-dark text-decoration-none">
                                {{ $item->name }}
                            </a>
                        </h5>
                        <p class="text-body mb-0">{{ Str::limit($item->description, 100) }}</p>
                        <div class="d-flex justify-content-between mt-3">
                            <span class="text-primary">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Suggested Menu End -->
@endsection 