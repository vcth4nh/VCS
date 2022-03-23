<?php

namespace App\Http\Controllers;

use App\Models\Msg;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MsgController extends Controller
{
    /**
     * Hiển thị trang danh sách học sinh
     *
     * @param $recv_uid
     * @return Application|Factory|View
     */
    public function index($recv_uid = null)
    {
        $student_list = User::student();
        $teacher_list = User::teacher();
        if ($recv_uid === null)
            return view('user-list', ['student_list' => $student_list, 'teacher_list' => $teacher_list]);

        $msg_list = Msg::get_msg(Auth::user()->uid, $recv_uid);
        return view('user-list', ['student_list' => $student_list, 'teacher_list' => $teacher_list, 'msg_list' => $msg_list]);
    }

    /**
     * Gửi tin nhắn
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'recv_uid' => ['required', 'exists:users,uid', function ($attribute, $value, $fail) {
                if (Auth::user()->uid == $value)
                    return $fail('');
            }],
            'text' => ['required', 'string', 'max:255'],
        ]);
        Msg::create([
            'send_uid' => Auth::user()->uid,
            'recv_uid' => $request->recv_uid,
            'text' => $request->text,
        ]);
        return back();
    }

    /**
     * Sửa tin nhắn
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'msg_id' => ['required', function ($attribute, $value, $fail) {
                if (Msg::where('msg_id', $value)->where('send_uid', Auth::user()->uid)->first() === null)
                    return $fail('');
            }],
            'text' => ['required', 'string', 'max:255'],
        ]);
        Msg::update_msg($request->msg_id, $request->text);
        return back();
    }

    /**
     * Xóa tin nhắn
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'msg_id' => ['required']
        ]);
        Msg::delete_msg($request->msg_id);
        return back();
    }
}
