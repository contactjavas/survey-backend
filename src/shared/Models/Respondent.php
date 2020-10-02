<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Respondent extends Model
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
        'genderId'            => 'integer',
        'religionId'          => 'integer',
        'educationId'         => 'integer',
        'activeOnSocialMedia' => 'integer',
    ];

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
