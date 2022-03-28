<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 */
class Submitted extends Model
{
    use HasFactory;

    protected $table = 'submitted';

    protected $primaryKey = 'sub_id';

    public $timestamps = false;

    protected $fillable = [
        'exer_id',
        'uid',
        'location',
        'original_name',
    ];

    /**
     * Lấy những bài làm đã được nộp của một bài tập nào đó
     *
     * @param $exer_id
     * @return Builder[]|Collection
     */
    public static function exer($exer_id)
    {
        return Submitted::query()
            ->where('exer_id', $exer_id)
            ->get();
    }

    /**
     * Tạo relationship của cột uid đến table users
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}
