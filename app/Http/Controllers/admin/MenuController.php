<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        // Xử lý sắp xếp
        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');

        if ($sort) {
            switch($sort) {
                case 'id':
                    $query->orderBy('id', $direction);
                    break;
                case 'category':
                    $query->join('categories', 'menu.category_code', '=', 'categories.category_code')
                          ->orderBy('categories.name', $direction)
                          ->select('menu.*');
                    break;
                case 'price':
                    $query->orderBy('price', $direction);
                    break;
                case 'status':
                    $query->orderBy('status', $direction);
                    break;
            }
        } else {
            $query->orderBy('id', 'asc'); // Mặc định sắp xếp theo ID tăng dần khi không có sort
        }

        $menus = $query->paginate(10);

        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'max:255',
                'unique:menu,name',
            ],
            'category_code' => 'required|exists:categories,category_code',
            'price' => 'required|numeric|min:0',
            'description' => 'required|min:10',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'name.required' => 'Vui lòng nhập tên món ăn',
            'name.unique' => 'Tên món ăn đã tồn tại',
            'category_code.required' => 'Vui lòng chọn danh mục',
            'category_code.exists' => 'Danh mục không tồn tại',
            'description.required' => 'Vui lòng nhập mô tả món ăn',
            'description.min' => 'Mô tả phải có ít nhất 10 ký tự',
            'price.required' => 'Vui lòng nhập giá món ăn',
            'price.numeric' => 'Giá phải là số',
            'price.min' => 'Giá không được âm',
            'image.required' => 'Vui lòng chọn ảnh món ăn',
            'image.image' => 'File phải là ảnh',
            'image.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước ảnh tối đa là 2MB'
        ]);

        $categoryCode = $request->category_code;
        $menuCode = 'MENU' . $categoryCode . date('Ymd');

        $count = Menu::where('menu_code', 'LIKE', $menuCode . '%')->count();
        if ($count > 0) {
            $menuCode = $menuCode . str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        }

        $menu = new Menu();
        $menu->menu_code = $menuCode;
        $menu->name = $request->name;
        $menu->slug = Str::slug($request->name);
        $menu->description = $request->description;
        $menu->price = $request->price;
        $menu->category_code = $request->category_code;
        $menu->status = $request->status ? 1 : 0;
        $menu->position = Menu::max('position') + 1;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/menu'), $imageName);
            $menu->image = 'uploads/menu/' . $imageName;
        }

        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Thêm món ăn thành công');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $categories = Category::where('status', 1)->get();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:menu,name,'.$id,
            'category_code' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'name.required' => 'Vui lòng nhập tên món ăn',
            'name.unique' => 'Tên món ăn đã tồn tại',
            'category_code.required' => 'Vui lòng chọn danh mục',
            'price.required' => 'Vui lòng nhập giá',
            'price.numeric' => 'Giá phải là số',
            'price.min' => 'Giá không được âm',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Định dạng hình ảnh không hợp lệ',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB'
        ]);

        $menu = Menu::findOrFail($id);
        
        $menu->name = $request->name;
        $menu->category_code = $request->category_code;
        $menu->price = $request->price;
        $menu->description = $request->description;
        $menu->status = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($menu->image && file_exists(public_path($menu->image))) {
                unlink(public_path($menu->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/menu'), $imageName);
            $menu->image = 'uploads/menu/' . $imageName;
        }

        $menu->save();

        $page = $request->input('current_page');
        if ($page) {
            return redirect()->route('menu.index', ['page' => $page])->with('success', 'Cập nhật món ăn thành công');
        }
        
        return redirect()->route('menu.index')->with('success', 'Cập nhật món ăn thành công');
    }

    public function destroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            
            if ($menu->image && file_exists(public_path($menu->image))) {
                unlink(public_path($menu->image));
            }
            
            $menu->delete();
            
            return response()->json([
                'status' => true,
                'message' => 'Xóa món ăn thành công'
            ]);
        } catch (\Exception $e) {
           
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi xóa món ăn: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePosition(Request $request)
    {
        $positions = $request->positions;
        foreach ($positions as $position) {
            Menu::where('id', $position['id'])
                ->update(['position' => $position['position']]);
        }
        
        return response()->json(['success' => true]);
    }
}