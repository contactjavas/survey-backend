<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Question extends Model
{
    /**
     * Disable "created_at" and "updated_at"
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Appended properties
     *
     * @var array
     */
    protected $appends = ['type'];

    /**
     * Get type attribute
     *
     * @return object
     */
    public function getTypeAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\QuestionType', 'question_type_id')
        ->first()
        ->name;
    }
}
