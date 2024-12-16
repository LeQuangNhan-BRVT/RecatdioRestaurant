@extends('layouts.front')

@section('title', 'Sửa đơn đặt bàn')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Sửa đơn đặt bàn #{{ $booking->id }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Form fields tương tự như form đặt bàn -->
                        <!-- Nhưng điền sẵn giá trị từ $booking -->

                        <button type="submit" class="btn btn-primary">
                            Cập nhật đơn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 