<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoneClub extends Model
{
     //use SoftDeletes;

     public $table = 'zone_clubs';
    
     const CREATED_AT = 'created_at';
     const UPDATED_AT = 'updated_at';
 
 
     protected $dates = ['deleted_at'];
 
 
     public $fillable = [         
         'id_zone',
         'id_club'
     ];
 
     /**
      * The attributes that should be casted to native types.
      *
      * @var array
      */
     protected $casts = [         
         'id_zone' => 'integer',
         'id_club' => 'integer'
     ];
 
     /**
      * Validation rules
      *
      * @var array
      */
     public static $rules = [
        'id_zone' => 'required',
        'id_club' => 'required'
     ];

     public function zone()
     {
        return $this->belongsTo(\App\Models\Zone::class, 'id_zone');
     }

     public function club()
     {
        return $this->belongsTo(\App\Models\Club::class, 'id_club');
     }
 
}
