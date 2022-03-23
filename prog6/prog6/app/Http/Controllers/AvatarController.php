<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvatarController extends Controller
{
    /**
     * Encode base 64 avatar đã được upload và lưu vào DB
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate(['avatar' => ['required', 'image', 'max:2048']]);
        $path = $request->file('avatar')->storePublicly('avatars');
        dd($path);
//        $image = base64_encode(file_get_contents($request->file('avatar')->path()));
//        User::findOrFail(Auth::user()->uid)->update(['avatar' => $image]);
    }
}
