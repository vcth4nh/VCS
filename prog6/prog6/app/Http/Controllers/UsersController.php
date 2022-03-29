<?php

namespace App\Http\Controllers;

use App\Models\Exers;
use App\Models\Msg;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UsersController extends Controller
{
    /**
     * Hiển thị trang chủ sau khi đã đăng nhập
     *
     * @param $notification
     * @return Application|Factory|View
     */
    public function index($notification = null)
    {
        $data = [
            'notification' => $notification,
            'msg_list' => Msg::get_recved_msg(Auth::user()->uid),
            'exer_list' => Exers::all(),
        ];

        if (Auth::user()->role == TEACHER) {
            $student_list = User::student();
            $teacher_list = User::teacher();
            return view('dashboard-teacher', [
                    'student_list' => $student_list,
                    'teacher_list' => $teacher_list,
                ] + $data
            );
        } else {
            $personal_info = User::uname_info(Auth::user()->username);
            return view('dashboard-student', [
                    'personal_info' => $personal_info,
                ] + $data
            );
        }
    }

    /**
     * Tạo mảng gồm nhũng thông tin cần được update
     *
     * @param $fields
     * Là mảng chứa tên những cột cần được update
     * Mặc định gồm những cột: username, password, fullname, email, phone
     *
     * @return array
     */
    private function update_helper(Request $request,$fields = ['username', 'fullname', 'email', 'phone'])
    {
        $arr = [];
        foreach ($fields as $key) {
            $arr += [$key => $request->__get($key)];
        }
        $arr += $request->password === null ? [] : ['password' => Hash::make($request->password)];
        return $arr;
    }

    /**
     * Lấy những thông tin mà học sinh cần update
     *
     * @return array
     */
    public function student_update(Request $request)
    {
        return $this->update_helper($request,['email', 'phone']);
    }

    /**
     * Lấy những thông tin mà giáo viên cần update
     *
     * @return array
     */
    public function teacher_update(Request $request)
    {
        return $this->update_helper($request);
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
            $success = User::update_student($this->student_update($request));
        } else {
            $request->validate([
                'uid' => ['required', 'is_student'],
                'username' => ['required', 'string', 'min:6', 'max:35', 'not_exist_with_another_uid:' . $request->uid],
                'fullname' => ['required', 'string', 'max:255'],
            ]);
            $success = User::update_student($this->teacher_update($request), $request->uid);
        }
        $notification = $success ? __('notification.update-user') : null;
        return $this->index($notification);
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
        $notification = $success ? __('notification.delete-user') : null;
        return $this->index($notification);
    }
}
