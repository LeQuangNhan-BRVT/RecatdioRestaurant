<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        Log::info('--- Bắt đầu quá trình đăng nhập ---');
        Log::info('Dữ liệu request:', $request->all());

        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Kiểm tra thông tin đăng nhập
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Kiểm tra role
            if (Auth::user()->role == 1) {
                Auth::logout();
                Log::warning('Đăng nhập thất bại: Tài khoản admin không được phép truy cập.', ['email' => $request->email]);
                return back()->withErrors([
                    'email' => 'Tài khoản này không có quyền truy cập.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();
            Log::info('Đăng nhập thành công:', ['user_id' => Auth::id(), 'email' => $request->email]);
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        Log::warning('Đăng nhập thất bại: Thông tin đăng nhập không chính xác.', ['email' => $request->email]);
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->only('email'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = Auth::id();
        Log::info('--- Bắt đầu quá trình đăng xuất ---', ['user_id' => $userId]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        Log::info('Đăng xuất thành công:', ['user_id' => $userId]);
        return redirect('/');
    }
}
