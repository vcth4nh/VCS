<?php

namespace App\Http\Controllers;

use App\Models\Exers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExersController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'exer' => ['required', 'max:5120'],
        ]);

        $file = $request->file('exer');
        $original_name = $file->getClientOriginalName();
        $path = basename($file->store('exers'));

        Exers::create([
            'location' => $path,
            'original_name' => $original_name
        ]);

        return redirect(route('dashboard.index'));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'exer_id' => ['required', 'exists:exercises'],
        ]);

        Exers::find($request->exer_id)->delete();

        return redirect(route('dashboard.index'));
    }

    public function download($path)
    {
        $original_name = Exers::where('location', $path)->firstOrFail()->original_name;
        $res = Storage::download('exers/' . $path, $original_name);
        return $res;
    }
}
