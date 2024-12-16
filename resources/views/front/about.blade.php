@extends('layouts.front')

@section('title', 'Giới thiệu')

@section('hero-title', 'Giới thiệu')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('front.home') }}">Trang chủ</a></li>
<li class="breadcrumb-item text-white active" aria-current="page">Giới thiệu</li>
@endsection

@section('content')
<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6 text-start">
                        <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.1s" src="{{ asset('front-assets/img/about-1.jpg') }}">
                    </div>
                    <div class="col-6 text-start">
                        <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.3s" src="{{ asset('front-assets/img/about-2.jpg') }}" style="margin-top: 25%;">
                    </div>
                    <div class="col-6 text-end">
                        <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.5s" src="{{ asset('front-assets/img/about-3.jpg') }}">
                    </div>
                    <div class="col-6 text-end">
                        <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.7s" src="{{ asset('front-assets/img/about-4.jpg') }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h5 class="section-title ff-secondary text-start text-primary fw-normal">Giới thiệu</h5>
                <h1 class="mb-4">Chào mừng đến với <i class="fa fa-utensils text-primary me-2"></i>Nhà hàng Recatdio</h1>
                <p class="mb-4">Nhà hàng được thành lập với mục tiêu làm hài lòng tất cả thực khách, không chỉ chất lượng của món ăn mà còn là sự phục vụ tận tâm.</p>
                <p class="mb-4">Bên cạnh đó, không gian nhà hàng trang trí hài hòa, đầy đủ tiện nghi, đảm bảo khách hàng có trải nghiệm tuyệt vời.</p>
                <div class="row g-4 mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                            <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">3</h1>
                            <div class="ps-4">
                                <p class="mb-0">Năm</p>
                                <h6 class="text-uppercase mb-0">kinh nghiệm</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                            <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">50</h1>
                            <div class="ps-4">
                                <p class="mb-0">Món ăn</p>
                                <h6 class="text-uppercase mb-0">đa dạng</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About End -->

<!-- Team Start -->
<div class="container-xxl pt-5 pb-3">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Thành viên</h5>
                    <h1 class="mb-5">Đội ngũ đầu bếp</h1>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item text-center rounded overflow-hidden">
                            <div class="rounded-circle overflow-hidden m-4">
                                <img class="img-fluid" src="{{ asset('front-assets/img/LQN.jpg') }}" alt="Bếp trưởng">
                            </div>
                            <h5 class="mb-0">Lê Quang Nhân</h5>
                            <small>Bếp trưởng</small>
                            <div class="d-flex justify-content-center mt-3">
                                <a class="btn btn-square btn-primary mx-1" href="https://www.facebook.com/quangnhan.le.9404"><i class="fab fa-facebook-f"></i></a>
                            
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="team-item text-center rounded overflow-hidden">
                            <div class="rounded-circle overflow-hidden m-4">
                                <img class="img-fluid" src="{{ asset('front-assets/img/TTN.jpg') }}" alt="Bếp phó">
                            </div>
                            <h5 class="mb-0">Trần Trọng Nhân</h5>
                            <small>Bếp phó</small>
                            <div class="d-flex justify-content-center mt-3">
                                <a class="btn btn-square btn-primary mx-1" href="https://www.facebook.com/nhantran2303"><i class="fab fa-facebook-f"></i></a>
                               
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- Team End -->
@endsection