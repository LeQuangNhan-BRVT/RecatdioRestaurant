@extends('admin.layouts.app')
@section('content')
<section class="content-header">
   <div class="container-fluid my-2">
       <div class="row mb-2">
           <div class="col-sm-6">
               <h1>Chỉnh sửa danh mục</h1>
           </div>
           <div class="col-sm-6 text-right">
               <a href="{{ route('categories.index') }}" class="btn btn-primary">Trở về</a>
           </div>
       </div>
   </div>
</section>
<section class="content">
   <div class="container-fluid">
       <form action="" method="post" id="categoryForm" enctype="multipart/form-data">
           @csrf
           @method('PUT')
           <div class="card">
               <div class="card-body">
                   <div class="row">
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="name">Tên danh mục</label>
                               <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}">
                               <p class="error"></p>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="category_code">Mã danh mục</label>
                               <input type="text" name="category_code" id="category_code" class="form-control" value="{{ $category->category_code }}">
                               <p class="error"></p>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="status">Trạng thái</label>
                               <select name="status" id="status" class="form-control">
                                   <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Hiện</option>
                                   <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Ẩn</option>
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="mb-3">
                               <label for="image">Hình ảnh</label>
                               <input type="file" name="image" id="image" class="form-control">
                               <p class="error"></p>
                           </div>
                           @if($category->image)
                           <div class="mb-3">
                               <img src="{{ asset($category->image) }}" alt="" width="100">
                           </div>
                           @endif
                       </div>
                       <div class="col-md-12">
                           <div class="mb-3">
                               <label for="description">Mô tả</label>
                               <textarea name="description" id="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
           <div class="pb-5 pt-3">
               <button type="submit" class="btn btn-primary">Cập nhật</button>
               <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Hủy</a>
           </div>
       </form>
   </div>
</section>
@endsection

@section('customjs')
<script>
   $('#categoryForm').submit(function(event) {
       event.preventDefault();
       var formData = new FormData(this);
       formData.append('_method', 'PUT');
       
       // Thêm loading state
       var submitBtn = $(this).find('button[type="submit"]');
       submitBtn.prop('disabled', true).html('Đang cập nhật...');
       
       $.ajax({
           url: '{{ route("categories.update", $category->id) }}',
           type: 'POST',
           data: formData,
           processData: false,
           contentType: false,
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           success: function(response) {
               if(response.status) {
                   alert(response.message); // Thêm thông báo thành công
                   window.location.href = '{{ route("categories.index") }}';
               }
           },
           error: function(xhr) {
               submitBtn.prop('disabled', false).html('Cập nhật'); // Reset button
               if(xhr.status === 422) {
                   var errors = xhr.responseJSON.errors;
                   $('.error').text(''); // Xóa các lỗi cũ
                   Object.keys(errors).forEach(function(key) {
                       $('#' + key).siblings('.error').text(errors[key][0]);
                   });
               } else {
                   alert('Có lỗi xảy ra. Vui lòng thử lại.');
               }
           },
           complete: function() {
               submitBtn.prop('disabled', false).html('Cập nhật'); // Reset button
           }
       });
   });
</script>
@endsection