<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function view()
    {
        $result = Category::select("name")->get();
        return view('category.view', ['categories' => $result]);
    }

    function create()
    {
        return view('category.create');
    }

    function add(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        Category::create($data);
        return redirect()->route('category');
    }
}
