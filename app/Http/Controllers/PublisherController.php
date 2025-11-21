<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url'
        ]);

        $publisher = Publisher::create($request->all());
        return redirect()->back()->with('success', 'Yayınevi başarıyla eklendi.');
    }

    public function destroy($id)
    {
        try {
            $publisher = Publisher::findOrFail($id);
            $publisher->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Yayınevi silinemedi.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url'
        ]);

        $publisher = Publisher::findOrFail($id);
        $publisher->update($request->all());
        return redirect()->back()->with('success', 'Yayınevi başarıyla güncellendi.');
    }
}
