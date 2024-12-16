@extends('admin.layouts.app')

@push('styles')
<style>
    .pagination {
        margin: 0;
        display: flex;
        justify-content: center;
        padding-left: 0;
        list-style: none;
        border-radius: 0.25rem;
    }

    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        cursor: not-allowed;
        background-color: #fff;
        border-color: #dee2e6;
    }

    .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #007bff;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    .pagination-info {
        margin-bottom: 10px;
        text-align: center;
        color: #6c757d;
    }

    .pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }
</style>
@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Danh sách danh mục</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.create') }}" class="btn btn-primary">Tạo danh mục mới</a>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th width="120">Mã danh mục</th>
                            <th>Tên danh mục</th>
                            <th width="100">Hình ảnh</th>
                            <th width="100">Trạng thái</th>
                            <th width="150">Ngày tạo</th>
                            <th width="150">Ngày cập nhật</th>
                            <th width="100">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->category_code }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" width="50">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
                            <td>
                                @if($category->status == 1)
                                    <span class="badge badge-success">Hiện</span>
                                @else
                                    <span class="badge badge-danger">Ẩn</span>
                                @endif
                            </td>
                            <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-category" 
                                   data-id="{{ $category->id }}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có danh mục nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <div class="pagination-container">
                    @if($categories->total() > 0)
                        <div class="pagination-info">
                            Hiển thị {{ $categories->firstItem() }} đến {{ $categories->lastItem() }} 
                            của {{ $categories->total() }} danh mục
                        </div>
                        {{ $categories->onEachSide(1)->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customjs')
<script>
    $(document).ready(function() {
        $('.delete-category').on('click', function() {
            var categoryId = $(this).data('id');
            if(confirm("Bạn có chắc chắn muốn xóa danh mục này không?")) {
                $.ajax({
                    url: '/admin/categories/' + categoryId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.status) {
                            window.location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Đã có lỗi xảy ra');
                    }
                });
            }
        });
    });
</script>
@endsection