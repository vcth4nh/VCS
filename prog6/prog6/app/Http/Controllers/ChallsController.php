<?php

namespace App\Http\Controllers;

use App\Models\Challs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChallsController extends Controller
{
    public function index()
    {
        $chall_list = Challs::all();
        return view('challenges', ['chall_list' => $chall_list]);
    }

    public function check(Request $request)
    {
        $chall_list = Challs::all();

        $request->validate([
            'answer' => ['required', 'string', 'max:255'],
            'chall_id' => ['required', 'regex:/^\d+$/']
        ]);

        $filepath = 'challs/' . $request->chall_id . '_' . $request->answer . '.txt';
        return view('challenges', ['chall_list' => $chall_list, 'content' => Storage::get($filepath)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'hint' => ['required', 'string', 'max:255'],
            'chall' => ['required', 'file', 'mimetypes:text/plain', 'mimes:txt', 'file_extension:txt', 'max:2048']
        ]);

        $chall_id = Challs::create([
            'hint' => $request->hint
        ])->chall_id;

        $file = $request->file('chall');
        $file_name = $chall_id . '_' . $file->getClientOriginalName();
        $file->storeAs('challs', $file_name);
        return redirect(route('challenges.index'));
    }
}
