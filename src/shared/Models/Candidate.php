<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Candidate extends Model
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
    protected $appends = ['type', 'survey'];

    /**
     * Get type attribute
     *
     * @return object
     */
    public function getTypeAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\CandidateType', 'candidate_type_id')
        ->first()
        ->name;
    }

    /**
     * Get type attribute
     *
     * @return object
     */
    public function getSurveyAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\Survey', 'survey_id')
        ->first()
        ->title;
    }
}
