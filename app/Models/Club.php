<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Club
 * @package App\Models
 * @version April 15, 2019, 7:19 pm UTC
 *
 * @property \App\Models\User idUser
 * @property \App\Models\Zone idZone
 * @property \Illuminate\Database\Eloquent\Collection editions

 * @property integer id_zone
 * @property integer id_user
 * @property string name
 * @property string address
 * @property string mobile_phone
 */
class Club extends Model
{
    //use SoftDeletes;

    public $table = 'clubs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [        
        'id_user',
        'id_city',
        'name',
        'address',       
        'phone', 
        'mobile_phone',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',        
        'id_user' => 'integer',
        'id_city' => 'integer',
        'name' => 'string',
        'address' => 'string',        
        'phone' => 'string',
        'mobile_phone' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [        
        'id_user' => 'required',
        'id_city' => 'required',
        'name' => 'required',
        'address' => 'required',              
        'mobile_phone' => 'required',
        'description' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'id_city');
    }
    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function editions()
    {
        return $this->belongsToMany(\App\Models\Edition::class, 'editions_clubs');
    }
}
