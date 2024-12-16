<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
    <a href="{{ route('front.home') }}" class="navbar-brand p-0">
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
