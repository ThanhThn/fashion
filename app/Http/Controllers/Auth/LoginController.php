<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    function loginUser(Request $request){
        $token = JWTAuth::attempt($request->only('email', 'password'));

        if(!$token){
            return response()->json([
                'success' => JsonResponse::HTTP_UNAUTHORIZED,
                'body' => [
                    'message' => 'Authentication Failed',
                ]
            ]);
        }

        return response()->json([
            "status" => JsonResponse::HTTP_OK,
            "body" => [
                'token' => $token,
            ]
        ]);
    }

    function infor()
    {
        $userId = Auth::user()->id;
        $user = User::where('id', $userId)->first();

        if(!$user) {
            return response()->json([
                'status' => JsonResponse::HTTP_UNAUTHORIZED,
                'body' => [
                    'message' => 'Authentication Failed',
                ]
            ]);
        }

        return response()->json([
            "status" => JsonResponse::HTTP_OK,
            "body" => [
                'user' => $user,
            ]
        ], JsonResponse::HTTP_OK);
    }
}
