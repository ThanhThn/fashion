<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function createOrder(Request $request)
    {
        $form = request()->input("form");
        $name = $request->input('name');
        $email = $request->input('email') ? $request->input('email') : null;
        $phone = $request->input('phone');
        $address = $request->input('address');
        $note = $request->input('note') ? $request->input('note') : null;
        $user_id = $request->input('user_id') ? $request->input('user_id') : null;
        $total = $request->input('total');
        $variants_order = $request->input('variants_order');
        $payment_method = $request->input('payment_method');

        $order = Order::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'note' => $note,
            'user_id' => $user_id,
            'order_total' => $total,
            'payment_method' => $payment_method,
            'order_status' => 0
        ]);
        $productIds = array_keys($variants_order);
        $products = Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        foreach ($variants_order as $productId => $requestedVariants) {
            $product = $products[$productId] ?? null;

            if (!$product) {
                continue;
            }

            $variants = $product->variants;

            foreach ($requestedVariants as $request) {
                $size = $request['size'];
                $requiredQuantity = $request['quantity'] ?? 0;
                foreach ($variants as &$variant) {
                    if($variant['size'] == $size){
                        $variant['stored'] -= $requiredQuantity;
                        break;
                    }
                }
            }
            $product->update(['variants' => $variants]);
            Order_Product::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'variants_order' => $requestedVariants,
            ]);
            if($form == 1 && $user_id){
                Cart::where("user_id", $user_id)->delete();
            }
        }
        return response()->json(['status' => JsonResponse::HTTP_OK, 'message' => 'Order created successfully'], JsonResponse::HTTP_OK);
    }
}
