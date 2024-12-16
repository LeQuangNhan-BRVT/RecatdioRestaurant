@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tạo danh mục</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Quay lại</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <form action="" method="post" id="categoryForm" name="categoryForm" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Tên danh mục</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên danh mục">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_code">Mã danh mục</label>
                                <input type="text" name="category_code" id="category_code" class="form-control" placeholder="Nhập mã danh mục">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Trạng thái</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Hiện</option>
                                    <option value="0">Ẩn</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="description">Mô tả</label>
                                <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Nhập mô tả danh mục"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image">Hình ảnh</label>
                                <input type="file" name="image" id="image" class="form-control">
                                <p class="text-muted mt-2">Cho phép JPG, JPEG, PNG. Tối đa 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Tạo mới</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Hủy</a>
            </div>
        </form>
    </div>
</section>
@endsection

@section('customjs')
<script>
$("#categoryForm").submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route("categories.store") }}',
        type: 'post',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(response){
            if(response.status == true) {
                window.location.href = '{{ route("categories.index") }}';
            } else {
                alert(response.message);
            }
        },
        error: function(response){
            if(response.status == 422) {
                var errors = response.responseJSON.errors;
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                
                $.each(errors, function(key, value){
                    $('#' + key).addClass('is-invalid');
                    $('#' + key).after('<div class="invalid-feedback">' + value[0] + '</div>');
                });
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại sau');
            }
        }
    });
});
</script>
@endsection

