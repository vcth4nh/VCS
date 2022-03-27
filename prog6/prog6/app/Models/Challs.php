<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Challs extends Model
{
    use HasFactory;

    protected $table = 'challs';

    protected $primaryKey = 'chall_id';

    public $timestamps = false;

    protected $fillable = [
        'hint'
    ];


}
