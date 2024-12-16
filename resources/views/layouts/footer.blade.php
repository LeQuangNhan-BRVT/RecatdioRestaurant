<div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Công ty</h4>
                <a class="btn btn-link" href="{{ route('front.about') }}">Về chúng tôi</a>
                <a class="btn btn-link" href="{{ route('front.contact') }}">Liên hệ</a>
                <a class="btn btn-link" href="{{ route('front.booking') }}">Đặt bàn</a>
                <a class="btn btn-link" href="">Chính sách bảo mật</a>
                <a class="btn btn-link" href="">Điều khoản & Điều kiện</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Liên hệ</h4>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>180 Đường Cao Lỗ, Quận 8, TP.HCM</p>
                <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>0123456789</p>
                <p class="mb-2"><i class="fa fa-envelope me-3"></i>recatdiorestaurant@gmail.com</p>
                <div class="d-flex pt-2">
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Giờ mở cửa</h4>
                <h5 class="text-light fw-normal">Thứ 2 - Thứ 7</h5>
                <p>09:00 - 22:00</p>
                <h5 class="text-light fw-normal">Chủ nhật</h5>
                <p>09:00 - 23:00</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Đăng ký</h4>
                <p>Đăng ký để nhận thông tin mới nhất từ chúng tôi.</p>
                <div class="position-relative mx-auto" style="max-width: 400px;">
                    <input class="form-control border-primary w-100 py-3 ps-4 pe-5" type="text" placeholder="Email của bạn">
                    <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Đăng ký</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="{{ route('front.home') }}">Nhà hàng Recatdio</a>, Lê Quang Nhân và Trần Trọng Nhân hợp tác thực hiện.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <a href="{{ route('front.home') }}">Trang chủ</a>
                        <a href="">Cookies</a>
                        <a href="">Trợ giúp</a>
                        <a href="">FAQs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 