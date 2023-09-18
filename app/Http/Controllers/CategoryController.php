<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        return response()->json(['categories' => $categories]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return response()->json(['category' => $category], 201);
    }
}
