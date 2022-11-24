<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Zone
 * @package App\Models
 * @version April 15, 2019, 7:16 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection clubs
 * @property \Illuminate\Database\Eloquent\Collection teams
 * @property string name
 */
class Zone extends Model
{
    //use SoftDeletes;

    public $table = 'zones';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'id_city',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'id_city' => 'integer',
        'description' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'id_city' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function clubs()
    {

        return $this->hasMany(\App\Models\ZoneClub::class, 'id_zone', 'id', 'zone_clubs')->join('clubs', 'zone_clubs.id_club', '=', 'clubs.id')->orderBy('clubs.name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function teams()
    {
        return $this->hasMany(\App\Models\Team::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'id_city');
    }
}
