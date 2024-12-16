@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container text-center my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Đăng nhập</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Đăng nhập</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="wow fadeInUp" data-wow-delay="0.2s">
                <div class="bg-light p-4 p-lg-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" placeholder="Email" 
                                           value="{{ old('email') }}" required>
                                    <label for="email">Email</label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Mật khẩu" required>
                                    <label for="password">Mật khẩu</label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" 
                                           id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Đăng nhập</button>
                            </div>
                            <div class="col-12 text-center">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        Quên mật khẩu?
                                    </a>
                                @endif
                                <p class="mt-3">Chưa có tài khoản? 
                                    <a href="{{ route('register') }}">Đăng ký ngay</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection