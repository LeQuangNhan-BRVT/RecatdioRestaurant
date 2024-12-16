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
                <h1>Danh sách món ăn</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('menu.create') }}" class="btn btn-primary">Thêm món ăn mới</a>
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
                            <th width="60">
                                <a href="{{ route('menu.index', ['sort' => 'id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'page' => request()->query('page')]) }}" class="text-dark">
                                    ID
                                    @if(request('sort') == 'id')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th width="120">Mã món</th>
                            <th>Tên món</th>
                            <th>
                                <a href="{{ route('menu.index', ['sort' => 'category', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'page' => request()->query('page')]) }}" class="text-dark">
                                    Danh mục
                                    @if(request('sort') == 'category')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th width="100">
                                <a href="{{ route('menu.index', ['sort' => 'price', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'page' => request()->query('page')]) }}" class="text-dark">
                                    Giá
                                    @if(request('sort') == 'price')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th width="100">Hình ảnh</th>
                            <th width="100">
                                <a href="{{ route('menu.index', ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'page' => request()->query('page')]) }}" class="text-dark">
                                    Trạng thái
                                    @if(request('sort') == 'status')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th width="100">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                        <tr>
                            <td>{{ $menu->id }}</td>
                            <td>{{ $menu->menu_code }}</td>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->category->name ?? 'N/A' }}</td>
                            <td>{{ number_format($menu->price) }} đ</td>
                            <td>
                                @if($menu->image)
                                    <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}" width="50">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
                            <td>
                                @if($menu->status == 1)
                                    <span class="badge badge-success">Hiện</span>
                                @else
                                    <span class="badge badge-danger">Ẩn</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('menu.edit', ['menu' => $menu->id, 'page' => request()->query('page')]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-menu" 
                                   data-id="{{ $menu->id }}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có món ăn nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <div class="pagination-container">
                    @if($menus->total() > 0)
                        <div class="pagination-info">
                            Hiển thị {{ $menus->firstItem() }} đến {{ $menus->lastItem() }} 
                            của {{ $menus->total() }} món ăn
                        </div>
                        {{ $menus->onEachSide(1)->links() }}
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.delete-menu').on('click', function() {
            var menuId = $(this).data('id');
            if(confirm("Bạn có chắc chắn muốn xóa món ăn này không?")) {
                $.ajax({
                    url: '/admin/menu/' + menuId,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        if(response.status) {
                            alert(response.message);
                            window.location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi:', error);
                        alert('Đã có lỗi xảy ra khi xóa món ăn');
                    }
                });
            }
        });
    });
</script>
@endsection