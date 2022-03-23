<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\MessageBag;

class UsersController extends Controller
{
    public function index($message = null)
    {
        if (Auth::user()->role == TEACHER) {
            $student_list = User::student();
            $teacher_list = User::teacher();
            return view('dashboard', ['student_list' => $student_list, 'teacher_list' => $teacher_list, 'message' => $message]);
        } else {
            $personal_info = User::info(['username' => Auth::user()->username]);
            return view('dashboard', ['personal_info' => $personal_info, 'message' => $message]);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'email' => ['string', 'email', 'max:360', 'nullable'],
            'phone' => ['phone_number', 'nullable'],
            'password' => [Rules\Password::defaults(), 'nullable']
        ]);
        if (Auth::user()->role == STUDENT) {
            $success = User::where('uid', Auth::user()->uid)
                ->update($request->update_student());
        } else {
            $request->validate([
                'uid' => ['required', 'exists:users'],
                'username' => ['required', 'string', 'min:6', 'max:35', 'not_exist_with_another_uid:' . $request->uid],
                'fullname' => ['required', 'string', 'max:255'],
            ]);
            $success = User::where('uid', $request->uid)
                ->update($request->update_teacher());
        }
        $message = $success ? __('notification.update-user') : null;
        return $this->index($message);
    }

    public function create(Request $request)
    {

    }

    public function destroy(Request $request)
    {
        $request->validate([
            'uid' => ['required', 'exists:users', 'is_student']
        ]);

        $success = User::findOrFail($request->uid)->delete();
        $message = $success ? __('notification.delete-user') : null;
        return $this->index($message);
    }
}
