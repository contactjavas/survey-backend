<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast.
     * After migrating from VPS to shared hosting, the target attr returned as string.
     * 
     * @see https://laracasts.com/discuss/channels/eloquent/eloquent-returns-int-as-string
     *
     * @var array
     */
    protected $casts = [
        'target' => 'integer',
    ];
}
