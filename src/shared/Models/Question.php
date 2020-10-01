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
     * The attributes that should be cast.
     * After migrating from VPS to shared hosting, the target attr returned as string.
     *
     * @see https://laracasts.com/discuss/channels/eloquent/eloquent-returns-int-as-string
     *
     * @var array
     */
    protected $casts = [
        'question_type_id' => 'integer',
        'allowAdd'         => 'integer'
    ];

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
