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
    protected $appends = ['gender', 'religion', 'education', 'province', 'regency', 'district', 'village'];

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

    /**
     * Get province attribute
     *
     * @return object
     */
    public function getProvinceAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\Province', 'province_id')
        ->first()
        ->name;
    }

    /**
     * Get regency attribute
     *
     * @return object
     */
    public function getRegencyAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\Regency', 'regency_id')
        ->first()
        ->name;
    }

    /**
     * Get district attribute
     *
     * @return object
     */
    public function getDistrictAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\District', 'district_id')
        ->first()
        ->name;
    }

    /**
     * Get village attribute
     *
     * @return object
     */
    public function getVillageAttribute()
    {
        return $this
        ->belongsTo('App\Shared\Models\Village', 'village_id')
        ->first()
        ->name;
    }
}
