<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function listItemsInCart(){
        $cart = Cart::with('product')->where('user_id', Auth::user()->id)->get();
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'cart' => $cart,
            ],
        ], JsonResponse::HTTP_OK);
    }

    public function addToCart(Request $request)
    {
        $cart = Cart::where('user_id', Auth::user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            $variants = $cart->variants;
            $flag = false;

            foreach ($variants as $key => $variant) {
                if ($variant['size'] == $request->size) {
                    $variants[$key]['quantity'] += $request->quantity;
                    $flag = true;
                    break;
                }
            }

            if (!$flag) {
                $variants[] = [
                    'size' => $request->size,
                    'quantity' => $request->quantity
                ];
            }

            Cart::where('user_id', Auth::user()->id)
                ->where('product_id', $request->product_id)
                ->update(['variants' => json_encode($variants)]);

            $result = Cart::where('user_id', Auth::user()->id)
                ->where('product_id', $request->product_id)
                ->first();
        } else {
            $variant = [
                [
                    'size' => $request->size,
                    'quantity' => $request->quantity
                ]
            ];

            $result = Cart::create([
                'user_id' => Auth::user()->id,
                'product_id' => $request->product_id,
                'variants' => json_encode($variant)
            ]);
        }

        if ($result) {
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'body' => [
                    'cart' => $result,
                ]
            ], JsonResponse::HTTP_OK);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'body' => [
                'message' => 'Something went wrong'
            ]
        ], JsonResponse::HTTP_OK);
    }

    public function removeFromCart(Request $request){
        $result = Cart::where('user_id', Auth::user()->id)
            ->where('product_id', $request->product_id)
            ->delete();
        if(!$result){
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'body' => [
                    'message' => 'Something went wrong'
                ]
            ], JsonResponse::HTTP_OK);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'message' => 'Item has been deleted'
            ]
        ], JsonResponse::HTTP_OK);
    }
}
