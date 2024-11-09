<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    function showRegistrationForm()
    {
        return view('auth.register');
    }

    function registerUser(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        if(User::where('email', $email)->exists()){
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'body'=> [
                    'message' => 'Email already exists',
                ]
            ],JsonResponse::HTTP_OK);
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 1
        ]);

        if($user){
            return response()->json([
                'status' => JsonResponse::HTTP_CREATED,
                'body' => [
                    'user' => $user,
                ]
            ], JsonResponse::HTTP_OK);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'body' => [
                'message' => 'Something went wrong',
            ]
        ], JsonResponse::HTTP_OK);
    }
}
