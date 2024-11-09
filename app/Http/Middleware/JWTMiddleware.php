<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Illuminate\Http\JsonResponse;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        try {

            $user = JWTAuth::parseToken()->authenticate();

            $token = explode(' ',$request->header('Authorization'));


        } catch (Exception $e) {

            // check token invalid
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                $response = [
                    'status' => JsonResponse::HTTP_UNAUTHORIZED,
                    'body' => ['C_E_005'],
                ];
                return response()->json($response,JsonResponse::HTTP_OK);
            }
            // check token expired
            else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                $response = [
                    'status' => JsonResponse::HTTP_UNAUTHORIZED,
                    'body' => ['C_E_004'],
                ];
                return response()->json($response,JsonResponse::HTTP_OK);
            }
            // check token exist
            else{
                $response = [
                    'status' => JsonResponse::HTTP_UNAUTHORIZED,
                    'body' => ['C_E_006'],
                ];
                return response()->json($response,JsonResponse::HTTP_OK);
            }
        }


        return $next($request);
    }
}
