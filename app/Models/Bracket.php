<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Bracket
 * @package App\Models
 * @version April 28, 2019, 9:43 am UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection 
 */
class Bracket extends Model
{
    //use SoftDeletes;

    public $table = 'brackets';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id_tournament',
        'id_zone',
        'id_category',
        'id_category_type',
        'flag_online',
        'note'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_tournament' => 'integer',        
        'id_zone' => 'integer',        
        'id_category' => 'integer',
        'id_category_type' => 'integer',
        'generated' => 'integer',
        'edit_mode' => 'integer',
        'flag_online' => 'integer',
        'note' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_tournament' => 'required',        
        'id_zone' => 'required',        
        'id_category' => 'required',
        'id_category_type' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'id_category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function categoryType()
    {
        return $this->belongsTo(\App\Models\CategoryType::class, 'id_category_type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tournament()
    {
        return $this->belongsTo(\App\Models\Tournament::class, 'id_tournament');
    }


    public function phases(){
        return $this->hasMany(\App\Models\Phase::class, 'id_bracket', 'id', 'phases');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function zone()
    {
        return $this->belongsTo(\App\Models\Zone::class, 'id_zone');
    }
}
