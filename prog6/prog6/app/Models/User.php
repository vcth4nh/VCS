<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'avatar'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Trả về danh sach học sinh
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function student()
    {
        return User::all()->where('role', '=', STUDENT);
    }

    /**
     * Trả về danh sách giáo viên
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function teacher()
    {
        return User::all()->where('role', '=', TEACHER);
    }

    /**
     * Lấy thông tin user từ username hoặc uid
     *
     * @param array $key
     * @param $role
     * @return User|User[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|never|null
     */
    public static function info(array $key, $role = null)
    {
        return match (array_key_first($key)) {
            'uid' => User::findOrFail($key['uid']),
            'username' => User::where('username', $key['username'])->firstOrFail(),
            default => abort(500)
        };
    }

    /**
     * Kiểm tra có tồn tại record nào không
     *
     * @return bool
     */
    public function any()
    {
        return $this->count() > 0;
    }
}
