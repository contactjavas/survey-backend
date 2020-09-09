<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Village extends Model
{
    /**
     * Disable "created_at" and "updated_at"
     *
     * @var boolean
     */
    public $timestamps = false;
}
