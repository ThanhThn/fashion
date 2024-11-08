<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    function view()
    {
        $products = Product::with('thumbnail')
            ->get();

        return view('product.view',['products'=>$products]);
    }

    function create()
    {
        $result = Category::all();
        return view('product.create', ['categories' => $result]);
    }

    function add(Request $request){
        $file = $request->file('thumbnail');
        Log::info($file);
        // Tạo tên file duy nhất để tránh trùng lặp
        $filename = time() . '_' . $file->getClientOriginalName();

        // Tải file lên Amazon S3
        $path = $file->storeAs(env('AWS_BUCKET'), $filename, 's3');

        // Lấy URL của file vừa tải lên
        $fileUrl = Storage::disk('s3')->url($path);

        $thumbnail = Image::create([
            'path' => $fileUrl
        ]);

        if($thumbnail){
            Product::create([
                'name' => $request->name,
                'sort_description' => $request->sort_description,
                'variants' => $request->variants,
                'description' => $request->description,
                'category_id' => $request->category,
                'thumbnail_id' => $thumbnail->id,
            ]);
            return redirect()->route('product');
        }else{
            return redirect()->route('product.create');
        }
    }

    function listProduct()
    {
        $result = Product::with(['thumbnail', 'category'])->get();
        foreach ($result as $product) {
            $product->variants = json_decode($product->variants);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => $result
        ], JsonResponse::HTTP_OK);
    }

    function detailProduct($id)
    {
        $product = Product::with(['thumbnail', 'category'])->find($id);
        $product->variants = json_decode($product->variants);
        if(!$product){
            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'body' => 'Not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => $product
        ], JsonResponse::HTTP_OK);
    }
}
