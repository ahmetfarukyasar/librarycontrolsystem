<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index(){
        $friends = Friend::all();
        return view('friends', compact('friends'));
    }

    public function add(Request $request){
        $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);

        $existingFriend = Friend::where('user_id', Auth::id())
            ->where('friend_id', $request->friend_id)
            ->first();
        
        if ($existingFriend) {
            return redirect()->back()->with('error', 'Bu kullanıcı zaten arkadaşınız.');
        }

        if ($request->friend_id == Auth::id()) {
            return redirect()->back()->with('error', 'Kendinizi arkadaş olarak ekleyemezsiniz.');
        }

        $friend = Friend::where('id', $request->friend_id);
        if (!$friend) {
            return redirect()->back()->with('error', 'Bu kullanıcı bulunamadı.');
        }

        Friend::create([
            'user_id' => Auth::id(),
            'friend_id' => $request->friend_id,
        ]);



        return redirect()->back()->with('success', 'Arkadaş başarıyla eklendi.');

    }
}
