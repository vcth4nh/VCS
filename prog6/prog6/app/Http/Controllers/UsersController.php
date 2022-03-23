<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class UsersController extends Controller
{
    /**
     * Hiển thị trang chủ sau khi đã đăng nhập
     *
     * @param $message
     * @return Application|Factory|View
     */
    public function index($message = null)
    {
        if (Auth::user()->role == TEACHER) {
            $student_list = User::student();
            $teacher_list = User::teacher();
            return view('dashboard', ['student_list' => $student_list, 'teacher_list' => $teacher_list, 'message' => $message]);
        } else {
            $personal_info = User::uname_info(Auth::user()->username);
            return view('dashboard', ['personal_info' => $personal_info, 'message' => $message]);
        }
    }


    /**
     * Cập nhật thông tin học sinh
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function update(Request $request)
    {
        $request->validate([
            'email' => ['string', 'email', 'max:360', 'nullable'],
            'phone' => ['phone_number', 'nullable'],
            'password' => [Rules\Password::defaults(), 'nullable']
        ]);
        if (Auth::user()->role == STUDENT) {
            $success = User::update_student($request->student_update());
        } else {
            $request->validate([
                'uid' => ['required', 'is_student'],
                'username' => ['required', 'string', 'min:6', 'max:35', 'not_exist_with_another_uid:' . $request->uid],
                'fullname' => ['required', 'string', 'max:255'],
            ]);
            $success = User::update_student($request->teacher_update(), $request->uid);
        }
        $message = $success ? __('notification.update-user') : null;
        return $this->index($message);
    }

    /**
     * Xóa học sinh
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'uid' => ['required', 'exists:users', 'is_student']
        ]);

        $success = User::delete_student($request->uid);
        $message = $success ? __('notification.delete-user') : null;
        return $this->index($message);
    }
}
