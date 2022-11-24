<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerPosition extends Model
{
    public $table = 'banner_positions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [       
        'position_name',
        'active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'position_name' => 'string',
        'active' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [        
        'position_name' => 'required'        
    ];
}
