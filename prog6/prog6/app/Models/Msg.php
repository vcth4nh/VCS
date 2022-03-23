<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     * Hiển thị các tin nhắn đã gửi đến một user khác
     *
     * @param $send_uid
     * @param $revc_uid
     * @return Builder[]|Collection
     */
    public static function get_msg($send_uid, $revc_uid)
    {
        return Msg::query()
            ->where('send_uid', $send_uid)
            ->where('recv_uid', $revc_uid)
            ->get();
    }

    /**
     * Cập nhật tin nhắn đã gửi
     *
     * @param $msg_id
     * @param $text
     * @return int
     */
    public static function update_msg($msg_id, $text)
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
    public static function delete_msg($msg_id)
    {
        return Msg::query()
            ->where('msg_id', $msg_id)
            ->where('send_uid', Auth::user()->uid)
            ->firstOrFail()
            ->delete();
    }
}
