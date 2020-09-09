<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Education extends Model
{
    /**
     * Disable "created_at" and "updated_at"
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'educations';
}
