<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Edition
 * @package App\Models
 * @version April 15, 2019, 7:15 pm UTC
 *
 * @property \App\Models\Image idImageLogo
 * @property \Illuminate\Database\Eloquent\Collection clubs
 * @property \Illuminate\Database\Eloquent\Collection teams
 * @property \Illuminate\Database\Eloquent\Collection rankings
 * @property \Illuminate\Database\Eloquent\Collection tournaments
 * @property string name
 * @property string description
 * @property string date_start
 * @property string date_end
 * @property string registration_deadline_date
 * @property integer id_image_logo
 */
class Edition extends Model
{
    //use SoftDeletes;

    public $table = 'editions';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_event',
        'edition_name',
        'edition_type',
        'edition_description',
        'edition_rules',
        'edition_zone_rules',
        'edition_awards',
        'logo',
        'subscription_fee'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_event' => 'integer',
        'edition_name' => 'string',
        'edition_type' => 'integer',
        'edition_description' => 'string',
        'edition_rules' => 'string',
        'edition_zone_rules' => 'string',
        'edition_awards' => 'string',
        'edition_zones_and_clubs' => 'string',
        'logo' => 'string',
        'subscription_fee' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_event' => 'required',
        'edition_name' => 'required',
        'edition_type' => 'required'
    ];


    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class, 'id_event');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function clubs()
    {
        return $this->belongsToMany(\App\Models\Club::class, 'editions_clubs');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function rankings()
    {
        return $this->hasMany(\App\Models\Ranking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tournaments()
    {
        return $this->hasMany(\App\Models\Tournament::class, 'id_edition', 'id', 'tournaments');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function zones()
    {
        return $this->hasMany(\App\Models\EditionZone::class, 'id_edition', 'id', 'editions_zones')->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function categoryTypes()
    {
        return $this->hasMany(\App\Models\EditionCategoryType::class, 'id_edition', 'id', 'editions_category_types');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function categories()
    {
        return $this->hasMany(\App\Models\EditionCategory::class, 'id_edition', 'id', 'editions_categories');
    }
}
