<?php

// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->whereNull('cat_id')->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validation logic

        Category::create($request->all());

        return redirect('/categories');
    }

    public function update(Request $request, $id)
    {
        // Validation logic

        Category::findOrFail($id)->update($request->all());

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Category::destroy($id);

        return response()->json(['success' => true]);
    }
}

