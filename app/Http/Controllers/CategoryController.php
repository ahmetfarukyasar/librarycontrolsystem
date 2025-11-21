<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create(Request $request)
    {
        $validation = [
            'category_name' => 'required|string|max:255',
        ];
        $request->validate($validation);
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->save();
        return redirect()->route('admin.manageCategories')->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories'
        ]);

        $category = Category::create($request->all());
        return redirect()->back()->with('success', 'Kategori başarıyla eklendi.');
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Kategori silinemedi.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,'.$id
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->back()->with('success', 'Kategori başarıyla güncellendi.');
    }
}
