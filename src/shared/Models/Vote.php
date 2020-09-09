<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Vote extends Model
{
    /**
     * Appended properties
     *
     * @var array
     */
    protected $appends = ['respondent', 'surveyor'];

    /**
     * Get respondent attribute
     *
     * @return object
     */
    public function getRespondentAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\Respondent', 'respondent_id')
        ->first();
    }

    /**
     * Get surveyor attribute
     *
     * @return object
     */
    public function getSurveyorAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\User', 'user_id')
        ->select('id', 'first_name', 'last_name', 'phone')
        ->first();
    }
}
