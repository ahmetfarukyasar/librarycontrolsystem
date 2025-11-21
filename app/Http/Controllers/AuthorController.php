<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string'
        ]);

        $author = Author::create($request->all());
        return redirect()->back()->with('success', 'Yazar başarıyla eklendi.');
    }

    public function destroy($id)
    {
        try {
            $author = Author::findOrFail($id);
            $author->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Yazar silinemedi.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string'
        ]);

        $author = Author::findOrFail($id);
        $author->update($request->all());
        return redirect()->back()->with('success', 'Yazar başarıyla güncellendi.');
    }
}
