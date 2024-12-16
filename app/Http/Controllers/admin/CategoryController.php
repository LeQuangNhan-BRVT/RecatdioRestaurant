<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::paginate(10);
        return view('admin.category.index', compact('categories'));
    }
    public function create(){
        $categories = Category::all();
        return view('admin.category.create', compact('categories'));
    }
    public function store(Request $request){
        $validator = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'category_code' => 'required|string|max:20|unique:categories,category_code',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'name.required' => 'Tên danh mục không được để trống',
            'name.unique' => 'Tên danh mục này đã tồn tại',
            'category_code.required' => 'Mã danh mục không được để trống',
            'category_code.unique' => 'Mã danh mục này đã tồn tại',
            'category_code.max' => 'Mã danh mục không được vượt quá 20 ký tự',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng jpg, jpeg hoặc png',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB'
        ]);

        try {
            $data = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_code' => strtoupper($request->category_code),
                'description' => $request->description,
                'status' => $request->status
            ];

            // Xử lý upload ảnh
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/categories'), $imageName);
                $data['image'] = 'uploads/categories/' . $imageName;
            }

            Category::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Tạo danh mục thành công'
            ]);
        } catch (\Exception $e) {
           
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi tạo danh mục: ' . $e->getMessage()
            ], 500);
        }
    }
    public function edit($category_id)
    {
        try {
            $category = Category::findOrFail($category_id);
            return view('admin.category.edit', compact('category'));
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Không tìm thấy danh mục');
        }
    }
    public function update(Request $request, $category_id)
    {
        try {
            $category = Category::findOrFail($category_id);
            $oldCategoryCode = $category->category_code;
            
            $validator = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category_id,
                'category_code' => 'required|string|max:20|unique:categories,category_code,' . $category_id,
                'description' => 'nullable|string',
                'status' => 'required|in:0,1',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ], [
                'name.required' => 'Tên danh mục không được để trống',
                'name.unique' => 'Tên danh mục này đã tồn tại',
                'category_code.required' => 'Mã danh mục không được để trống',
                'category_code.unique' => 'Mã danh mục này đã tồn tại',
                'category_code.max' => 'Mã danh mục không được vượt quá 20 ký tự',
                'image.image' => 'File phải là hình ảnh',
                'image.mimes' => 'Hình ảnh phải có định dạng jpg, jpeg hoặc png',
                'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB'
            ]);

            $data = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_code' => strtoupper($request->category_code),
                'description' => $request->description,
                'status' => $request->status
            ];

            if ($request->hasFile('image')) {
                // Xóa ảnh cũ nếu có
                if ($category->image && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/categories'), $imageName);
                $data['image'] = 'uploads/categories/' . $imageName;
            }

            DB::beginTransaction();
            try {
                // Cập nhật category
                $category->update($data);

                // Cập nhật category_code trong bảng menu nếu mã danh mục thay đổi
                if ($oldCategoryCode !== strtoupper($request->category_code)) {
                    Menu::where('category_code', $oldCategoryCode)
                        ->update(['category_code' => strtoupper($request->category_code)]);
                }

                DB::commit();
                
                return response()->json([
                    'status' => true,
                    'message' => 'Cập nhật danh mục thành công'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật danh mục: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy($category_id)
    {
        try {
            $category = Category::findOrFail($category_id);
            
            // Kiểm tra xem danh mục có món ăn nào không
            if($category->menu()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể xóa danh mục này vì có chứa món ăn'
                ]);
            }
            
            $category->delete();
            
            return response()->json([
                'status' => true,
                'message' => 'Xóa danh mục thành công'
            ]);
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi xóa danh mục: ' . $e->getMessage()
            ], 500);
        }
    }
}
