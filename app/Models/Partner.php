<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public $table = 'partners';
    
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
        'email',
        'description',
        'website'
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
        'email' => 'string',
        'description' => 'string',
        'website' => 'string'
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
        
}
