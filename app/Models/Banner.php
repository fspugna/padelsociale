<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $table = 'banners';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [       
        'id_partner',
        'filename',
        'action'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_partner' => 'integer',        
        'filename' => 'string',
        'action' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [        
        'id_partner' => 'required',
        'filename' => 'required',
        'action' => 'required'
    ];

    public function partner()
    {
        return $this->belongsTo(\App\Models\Partner::class, 'id_partner');
    }

    public function positionings()
    {
        return $this->hasMany(\App\Models\BannerPositioning::class, 'id_banner');
    }

    public function cities()
    {
        return $this->hasMany(\App\Models\BannerCity::class, 'id_banner');
    }

    public function edition()
    {
        return $this->belongsTo(\App\Models\Edition::class, 'id_edition');
    }
}
