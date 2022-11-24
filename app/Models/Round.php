<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Round
 * @package App\Models
 * @version April 15, 2019, 7:22 pm UTC
 *
 * @property \App\Models\Group idGroup  
 * @property \Illuminate\Database\Eloquent\Collection teams
 * @property \Illuminate\Database\Eloquent\Collection matches
 * @property integer id_group
 * @property string round
 */
class Round extends Model
{
    //use SoftDeletes;

    public $table = 'rounds';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_group',
        'name',
        'matchcode',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_group' => 'integer',
        'name' => 'string',
        'matchcode' => 'integer',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_group' => 'required',
        'name' => 'required',
        'matchcode' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function group()
    {
        return $this->belongsTo(\App\Models\Group::class, 'id_group');
    }
    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function matchcodes()
    {
        return $this->belongsTo(\App\Models\Matchcode::class, 'matchcode', 'id' , 'matchcodes');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function matches()
    {
        return $this->hasMany(\App\Models\Match::class, 'matchcode', 'matchcode' , 'matches');
    }
    
    public function macro_matches()
    {
        return $this->hasMany(\App\Models\MacroMatch::class, 'matchcode', 'matchcode' , 'macro_matches');
    }
}
