<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    function showLoginForm()
    {
        return view('auth.login');
    }
    function login(Request $request){
        // Validate thông tin đầu vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Kiểm tra thông tin đăng nhập
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Nếu thông tin đăng nhập đúng, chuyển hướng đến dashboard
            $request->session()->regenerate(); // Tạo lại session để bảo mật tốt hơn
            return redirect()->intended('dashboard');
        }

        // Nếu thông tin đăng nhập sai, quay lại trang đăng nhập với lỗi
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput(); // Giữ lại giá trị input cũ ngoại trừ mật khẩu
    }
}
