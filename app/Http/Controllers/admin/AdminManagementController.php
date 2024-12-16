<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 1); // Chỉ lấy admin

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone_number', 'like', "%$search%");
            });
        }

        $admins = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:10|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 1, // Role admin
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Tạo admin mới thành công');
    }

    public function edit($id)
    {
        $admin = User::where('role', 1)->findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::where('role', 1)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'phone_number' => ['required', 'string', 'max:10', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $adminData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ];

        if ($request->filled('password')) {
            $adminData['password'] = Hash::make($request->password);
        }

        $admin->update($adminData);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Cập nhật admin thành công');
    }

    public function destroy($id)
    {
        try {
            $admin = User::where('role', 1)->findOrFail($id);
            
            // Không cho phép xóa admin cuối cùng
            if (User::where('role', 1)->count() <= 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể xóa admin cuối cùng'
                ], 400);
            }

            $admin->delete();
            
            return response()->json([
                'status' => true,
                'message' => 'Xóa admin thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi xóa admin: ' . $e->getMessage()
            ], 500);
        }
    }
} 