<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerCity extends Model
{
    public $table = 'banner_cities';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [       
        'id_banner',
        'id_city'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_banner' => 'integer',        
        'id_city' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [        
        'id_banner' => 'required',
        'id_city' => 'required'
    ];

    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'id_city');
    }

    public function banner()
    {
        return $this->belongsTo(\App\Models\Banner::class, 'id_banner');
    }
}
