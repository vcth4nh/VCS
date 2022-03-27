<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;


/**
 * @mixin Builder
 */
class Msg extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $primaryKey = 'msg_id';

    protected $fillable = [
        'send_uid',
        'recv_uid',
        'created_at',
        'updated_at',
        'text'
    ];

    /**
     * Tạo relationship của cột send_uid đến table users
     *
     * @return BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'send_uid');
    }

    /**
     * Tạo relationship của cột recv_uid đến table users
     *
     * @return BelongsTo
     */
    public function recver()
    {
        return $this->belongsTo(User::class, 'recv_uid');
    }

    /**
     * Hiển thị các tin nhắn đã gửi đến các user khác
     *
     * @param $send_uid
     * @param null $recv_uid
     * Mặc định là null, nếu được truyền vào uid của user khác thì sẽ
     * hiển thị tin nhắn đến user đó
     *
     * @return Builder[]|Collection
     */
    public static function get_sent_msg($send_uid, $recv_uid = null)
    {
        $msg_list = Msg::query()
            ->where('send_uid', $send_uid);

        return match ($recv_uid) {
            null => $msg_list->where('recv_uid', $recv_uid)->get(),
            default => $msg_list->get()
        };
    }

    public static function get_recved_msg($recv_uid)
    {
        return Msg::query()
            ->where('recv_uid', $recv_uid)
            ->get();
    }

    /**
     * Cập nhật tin nhắn đã gửi
     *
     * @param $msg_id
     * @param $text
     * @return int
     */
    public
    static function update_msg($msg_id, $text)
    {
        return Msg::query()
            ->where('msg_id', $msg_id)
            ->where('send_uid', Auth::user()->uid)
            ->update(['text' => $text]);
    }

    /**
     * Xóa tin nhắn đã gửi
     *
     * @param $msg_id
     * @return bool|null
     */
    public
    static function delete_msg($msg_id)
    {
        return Msg::query()
            ->where('msg_id', $msg_id)
            ->where('send_uid', Auth::user()->uid)
            ->firstOrFail()
            ->delete();
    }
}
