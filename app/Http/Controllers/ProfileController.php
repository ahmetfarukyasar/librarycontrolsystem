<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $avatarName = $user->id.'_avatar'.time().'.'.$request->avatar->extension();
        $request->avatar->move(public_path('avatars'), $avatarName);

        $user->avatar = $avatarName;
        $user->save();

        return back()->with('success', 'Profil resmi başarıyla güncellendi.');
    }
}
