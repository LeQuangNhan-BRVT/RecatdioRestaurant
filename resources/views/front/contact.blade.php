@extends('layouts.front')

@section('title', 'Liên hệ')

@section('hero-title', 'Liên hệ')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('front.home') }}">Trang chủ</a></li>
<li class="breadcrumb-item text-white active" aria-current="page">Liên hệ</li>
@endsection

@section('content')
<!-- Contact Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Liên hệ</h5>
            <h1 class="mb-5">Liên hệ với chúng tôi</h1>
        </div>
        <div class="row g-4">
            <div class="col-12">
                <div class="row gy-4">
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Facebook Messenger</h5>
                        <p><i class="fa fa-phone text-primary me-2"></i>Recatdio Restaurant</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Zalo</h5>
                        <p><i class="fa fa-phone text-primary me-2"></i>0123456789</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Email</h5>
                        <p><i class="fa fa-envelope-open text-primary me-2"></i>recatdiorestaurant@gmail.com</p>
                    </div>
                </div>
            </div>
            <div class="col-md-12 wow fadeIn" data-wow-delay="0.1s">
                <iframe class="position-relative rounded w-100 h-100"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d979.9883424004494!2d106.67780807281136!3d10.738077410750098!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f62a90e5dbd%3A0x674d5126513db295!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2jhu4cgU8OgaSBHw7Ju!5e0!3m2!1svi!2s!4v1732807912410!5m2!1svi!2s"
                    frameborder="0" style="min-height: 350px; border:0;" allowfullscreen="" aria-hidden="false"
                    tabindex="0"></iframe>
            </div>
            
        </div>
    </div>
</div>
<!-- Contact End -->
@endsection