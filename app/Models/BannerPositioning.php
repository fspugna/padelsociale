<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerPositioning extends Model
{
    public $table = 'banner_positionings';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [       
        'id_banner',
        'id_banner_positions'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_banner' => 'integer',        
        'id_banner_positions' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [        
        'id_banner' => 'required',
        'id_banner_positions' => 'required'
    ];

    public function position()
    {
        return $this->belongsTo(\App\Models\BannerPosition::class, 'id_banner_positions');
    }

    public function banner()
    {
        return $this->belongsTo(\App\Models\Banner::class, 'id_banner');
    }
}
