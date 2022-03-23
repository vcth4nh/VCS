<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'uid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'fullname',
        'email',
        'phone',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Trả về danh sach học sinh
     *
     * @return Collection
     */
    public static function student()
    {
        return User::query()
            ->where('role', '=', STUDENT)
            ->get();
    }

    /**
     * Trả về danh sách giáo viên
     *
     * @return Collection
     */
    public static function teacher()
    {
        return User::query()
            ->where('role', '=', TEACHER)
            ->get();
    }

//    /**
//     * Lấy thông tin user từ username hoặc uid
//     *
//     * @param array $key
//     * @return User|User[]|Collection|Model|never|null
//     */
//    public static function info(array $key)
//    {
//        return match (array_key_first($key)) {
//            'uid' => User::findOrFail($key['uid']),
//            'username' => User::where('username', $key['username'])->firstOrFail(),
//            default => abort(500)
//        };
//    }

    /**
     * Lấy thông tin người dùng từ username
     *
     * @param $username
     * @return User|Model
     */
    public static function uname_info($username)
    {
        return User::where('username', $username)->firstOrFail();
    }

    /**
     * Cập nhật thông tin học sinh
     *
     * @param $info
     * @param null $uid
     * @return int
     */
    public static function update_student($info, $uid = null)
    {
        $uid = $uid ?? Auth::user()->uid;
        return User::query()
            ->where('uid', $uid)
            ->update($info);
    }

    /**
     * Xóa học sinh
     *
     * @param $uid
     * @return bool|null
     */
    public static function delete_student($uid)
    {
        return User::query()
            ->where('role', STUDENT)
            ->findOrFail($uid)
            ->delete();
    }
}
