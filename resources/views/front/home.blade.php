<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Nhà hàng Recatdio</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('front-assets/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front-assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front-assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('front-assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('front-assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar & Hero Start -->
        <div class="container-xxl position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <h1 class="text-primary m-0"><i class="fa fa-utensils me-3"></i>Nhà hàng Recatdio</h1>

                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0 pe-4">
                        <a href="{{ route('front.home') }}" class="nav-item nav-link active">Trang chủ</a>
                        <a href="{{ route('front.about') }}" class="nav-item nav-link">Giới thiệu</a>
                        <a href="{{ route('front.service') }}" class="nav-item nav-link">Dịch vụ</a>
                        <a href="{{ route('front.menu') }}" class="nav-item nav-link">Thực đơn</a>
                        <a href="{{ route('front.contact') }}" class="nav-item nav-link">Liên hệ</a>

                        <!-- Thêm mục tài khoản -->
                        @guest
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Tài khoản</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{ route('login') }}" class="dropdown-item">Đăng nhập</a>
                                <a href="{{ route('register') }}" class="dropdown-item">Đăng ký</a>
                            </div>
                        </div>
                        @else
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">Thông tin cá nhân</a>
                                <a href="{{ route('bookings.index') }}" class="dropdown-item">Lịch sử đặt bàn</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                        class="dropdown-item"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Đăng xuất
                                    </a>
                                </form>
                            </div>
                        </div>
                        @endguest
                    </div>
                    <a href="{{ route('front.booking') }}" class="btn btn-primary py-2 px-4">Đặt bàn</a>
                </div>
            </nav>

            <div class="container-xxl py-5 bg-dark hero-header mb-5">
                <div class="container my-5 py-5">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-6 text-center text-lg-start">
                            <h1 class="display-3 text-white animated slideInLeft">Thưởng thức<br>ẩm thực tuyệt vời</h1>
                            <p class="text-white animated slideInLeft mb-4 pb-2">Chúng tôi có những đầu bếp chất lượng cao, được đào tạo bài bản. Đảm bảo các món ăn đều ngon miệng, hấp dẫn mang hơi hướng ẩm thực Việt Nam hòa trộn với ẩm thực nước ngoài.</p>
                            <a href="{{ route('front.booking') }}" class="btn btn-primary py-sm-3 px-sm-5 me-3 animated slideInLeft">Đặt bàn</a>
                        </div>
                        <div class="col-lg-6 text-center text-lg-end overflow-hidden">
                            <img class="img-fluid" src="{{ asset('front-assets/img/hero.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->


        <!-- Service Start -->
        <div class="container-xxl py-5">
            <div class="container">
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


        <!-- About Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <img class="about-img" src="{{ asset('front-assets/img/space.jpg') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="section-title ff-secondary text-start text-primary fw-normal">Giới thiệu</h5>
                        <h1 class="mb-4">Chào mừng đến với <i class="fa fa-utensils text-primary me-2"></i>Nhà hàng Recatdio</h1>
                        <p class="mb-4">Nhà hàng được thành lập với mục tiêu làm hài lòng tất cả thực khách, không chỉ chất lượng của món ăn mà còn là sự phục vụ tận tâm. A</p>
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
                        <a class="btn btn-primary py-3 px-5 mt-2" href="{{ route('front.about') }}">Đọc thêm</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


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




       <!-- Reservation Start -->
       <div class="bg-dark py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="p-5 wow fadeInUp text-center" data-wow-delay="0.2s">
                            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Đặt Bàn</h5>
                            <h1 class="text-white mb-4">Đặt Bàn Trực Tuyến</h1>
                            <a href="{{ route('front.booking') }}" class="btn btn-primary py-sm-3 px-sm-5 me-3 animated slideInLeft">Đặt bàn ngay!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Reservation End -->





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
->




        <!-- Footer Start -->
        @include('layouts.footer')
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('front-assets/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('front-assets/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('front-assets/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('front-assets/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('front-assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('front-assets/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('front-assets/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('front-assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('front-assets/js/main.js') }}"></script>
</body>

</html>