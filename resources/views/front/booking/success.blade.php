@extends('layouts.front')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                    <h2 class="card-title">Đặt bàn thành công!</h2>
                    <p class="card-text">
                        Cảm ơn bạn đã đặt bàn tại nhà hàng chúng tôi. 
                        Nhân viên sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận.
                    </p>
                    <hr>
                    <div class="text-center">
                        <a href="{{ route('front.home') }}" class="btn btn-primary">
                            Về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
