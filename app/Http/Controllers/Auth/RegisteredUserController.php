<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        Log::info('--- Bắt đầu quá trình đăng ký ---');
        Log::info('Dữ liệu request:', $request->all());

        $request->validate([
            // ... (validation rules)
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 0;
        $user->save();

        Log::info('Người dùng đã được tạo:', ['user_id' => $user->id, 'email' => $user->email]);

        event(new Registered($user));

        Auth::login($user);
        Log::info('Người dùng đã đăng nhập sau khi đăng ký:', ['user_id' => $user->id]);

        // Thêm code hủy session cũ
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::info('Đã hủy session cũ và tạo session mới.');

        return redirect(RouteServiceProvider::HOME);
    }
}
