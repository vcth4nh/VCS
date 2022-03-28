<?php

namespace App\Http\Controllers;

use App\Models\Exers;
use App\Models\Submitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmittedController extends Controller
{
    public function index($exer_id)
    {
        return view('submitted', [
            'submitted_list' => Submitted::exer($exer_id),
            'original_name' => Exers::name_from_id($exer_id),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'exer_id' => ['required', 'exists:exercises'],
            'submitted' => ['required', 'max:5120']
        ]);

        $file = $request->file('submitted');
        $original_name = $file->getClientOriginalName();
        $path = basename($file->store('submitted'));

        Submitted::create([
            'exer_id' => $request->exer_id,
            'uid' => Auth::user()->uid,
            'location' => $path,
            'original_name' => $original_name
        ]);

        return redirect(route('dashboard.index'));
    }

    public function download($path)
    {
        $original_name = Submitted::where('location', $path)->firstOrFail()->original_name;
        return Storage::download('exers/' . $path, $original_name);
    }
}
