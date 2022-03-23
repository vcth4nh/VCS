<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class MsgController extends Controller
{
    public function index()
    {
        $student_list = User::student();
        $teacher_list = User::teacher();
        return view('user-list', ['student_list' => $student_list, 'teacher_list' => $teacher_list]);
    }
}
