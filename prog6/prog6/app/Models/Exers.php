<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Exers extends Model
{
    use HasFactory;

    protected $table = 'exercises';

    protected $primaryKey = 'exer_id';

    public $timestamps = false;

    protected $fillable = [
        'location',
        'original_name'
    ];

    public static function name_from_id($exer_id)
    {
        return Exers::query()
            ->findOrFail($exer_id)['original_name'];
    }


}
