<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Respondent extends Model
{
    use SoftDeletes;

    /**
     * Appended properties
     *
     * @var array
     */
    protected $appends = ['gender', 'religion', 'education'];

    /**
     * Get gender attribute
     *
     * @return object
     */
    public function getGenderAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\Gender', 'gender_id')
        ->first()
        ->name;
    }

    /**
     * Get gender attribute
     *
     * @return object
     */
    public function getReligionAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\Religion', 'religion_id')
        ->first()
        ->name;
    }

    /**
     * Get gender attribute
     *
     * @return object
     */
    public function getEducationAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\Education', 'education_id')
        ->first()
        ->name;
    }
}
