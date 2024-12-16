<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            // Process login here
            if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                $admin = Auth::guard('admin')->user();
                if($admin->role == 1){
                    return redirect()->route('admin.dashboard');
                }else{
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error', 'Tài khoản của bạn không có quyền quản trị!');
                }
            }else{
                return redirect()->route('admin.login')->with('error', 'Email hoặc mật khẩu không đúng');
            }
        } else {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|max:10',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        if ($validator->passes()) {
            $admin = new User;
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone_number = $request->phone_number;
            $admin->password = Hash::make($request->password);
            $admin->role = 1; // Set role là admin
            $admin->save();

            return redirect()->route('admin.login')
                ->with('success', 'Tạo tài khoản admin thành công!');
        } else {
            return redirect()->route('admin.create')
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }
    }

    public function forgotPassword()
    {
        return view('admin.forgot-password');
    }

    public function forgotPasswordProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Xóa token cũ nếu có
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        $token = Str::random(64);

        // Thêm token mới
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('admin.emails.forgot-password', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Đặt lại mật khẩu');
        });

        return redirect()->back()->with('success', 'Chúng tôi đã gửi link đặt lại mật khẩu vào email của bạn!');
    }

    public function resetPassword($token)
    {
        return view('admin.reset-password', ['token' => $token]);
    }

    public function resetPasswordProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])->first();

        if (!$updatePassword) {
            return redirect()->back()->with('error', 'Token không hợp lệ!');
        }

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')
            ->where(['email' => $request->email])->delete();

        return redirect()->route('admin.login')
            ->with('success', 'Mật khẩu của bạn đã được thay đổi!');
    }

    public function settings()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.settings', compact('admin'));
    }

    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|max:10'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::find(Auth::id());
        if ($user) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->save();

            return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
        }

        return redirect()->back()->with('error', 'Không tìm thấy người dùng!');
    }

    public function changePassword()
    {
        return view('admin.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::find(Auth::id());
        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng!');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng'])
                ->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
