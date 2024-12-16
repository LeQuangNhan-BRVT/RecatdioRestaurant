@extends('layouts.front')

@section('title', 'Dịch vụ của chúng tôi')

@section('hero-title', 'Dịch vụ')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('front.home') }}">Trang chủ</a></li>
<li class="breadcrumb-item text-white active" aria-current="page">Dịch vụ</li>
@endsection

@section('content')
<!-- Service Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Dịch vụ của chúng tôi</h5>
            <h1 class="mb-5">Khám phá dịch vụ của chúng tôi</h1>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item rounded pt-3 h-100">
                    <div class="p-4 d-flex flex-column h-100">
                        <i class="fa fa-3x fa-user-tie text-primary mb-4"></i>
                        <h5>Đầu bếp thượng hạng</h5>
                        <p class="mb-0">Có nhiều năm kinh nghiệm làm việc trong các nhà hàng lớn, với đa dạng các món ăn.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-utensils text-primary mb-4"></i>
                        <h5>Chất lượng hảo hạng</h5>
                        <p>Đảm bảo các món ăn đều ngon miệng, hấp dẫn mang hơi hướng ẩm thực Việt Nam hòa trộn với ẩm thực nước ngoài.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-cart-plus text-primary mb-4"></i>
                        <h5>Đặt bàn online</h5>
                        <p>Dễ dàng đặt bàn online, tiết kiệm thời gian và công sức.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-headset text-primary mb-4"></i>
                        <h5>Phục vụ tận tình</h5>
                        <p>Phục vụ tận tình, chu đáo, đảm bảo khách hàng có trải nghiệm tuyệt vời.</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- Service End -->

<!-- Menu Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Dịch vụ đặc biệt</h5>
            <h1 class="mb-5">Dịch vụ phổ biến nhất</h1>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <img class="flex-shrink-0 img-fluid rounded" src="{{ asset('front-assets/img/tieccuoi.jpg') }}" alt="" style="width: 80px;">
                    <div class="w-100 d-flex flex-column text-start ps-4">
                        <h5 class="d-flex justify-content-between border-bottom pb-2">
                            <span>Tiệc cưới</span>
                        </h5>
                        <small class="fst-italic">Tổ chức tiệc cưới với không gian sang trọng, ấm cúng</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <img class="flex-shrink-0 img-fluid rounded" src="{{ asset('front-assets/img/tiecgiadinh.jpg') }}" alt="" style="width: 80px;">
                    <div class="w-100 d-flex flex-column text-start ps-4">
                        <h5 class="d-flex justify-content-between border-bottom pb-2">
                            <span>Tiệc gia đình</span>
                        </h5>
                        <small class="fst-italic">Tổ chức tiệc gia đình, sinh nhật với không gian riêng tư</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <img class="flex-shrink-0 img-fluid rounded" src="{{ asset('front-assets/img/tieccongty.jpg') }}" alt="" style="width: 80px;">
                    <div class="w-100 d-flex flex-column text-start ps-4">
                        <h5 class="d-flex justify-content-between border-bottom pb-2">
                            <span>Tiệc công ty</span>
                        </h5>
                        <small class="fst-italic">Tổ chức tiệc công ty, hội nghị với đầy đủ thiết bị</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center">
                    <img class="flex-shrink-0 img-fluid rounded" src="{{ asset('front-assets/img/tiectainha.jpg') }}" alt="" style="width: 80px;">
                    <div class="w-100 d-flex flex-column text-start ps-4">
                        <h5 class="d-flex justify-content-between border-bottom pb-2">
                            <span>Tiệc tại nhà</span>
                        </h5>
                        <small class="fst-italic">Dịch vụ nấu tiệc tại nhà chuyên nghiệp</small>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="bg-none py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="p-5 wow fadeInUp text-center" data-wow-delay="0.2s">
                            
                            <a href="{{ route('front.contact') }}" class="btn btn-primary w-100 py-3">Liên Hệ Ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>
<!-- Menu End -->
@endsection