<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Group
 * @package App\Models
 * @version April 15, 2019, 7:22 pm UTC
 *
 * @property \App\Models\Category idCategory
 * @property \Illuminate\Database\Eloquent\Collection rounds 
 * @property integer id_category
 * @property string name
 */
class Group extends Model
{
    //use SoftDeletes;

    public $table = 'groups';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_division',
        'name',
        'flag_online'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_division' => 'integer',
        'name' => 'string',
        'flag-online' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_division' => 'required',
        'name' => 'required',
        'flag_online' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'id_category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function rounds()
    {
        return $this->hasMany(\App\Models\Round::class, 'id_group');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function teams()
    {
        return $this->hasMany(\App\Models\GroupTeam::class, 'id_group', 'id', 'group_teams');        
    }
    
    public function macro_teams()
    {
        return $this->hasMany(\App\Models\GroupMacroTeam::class, 'id_group', 'id', 'group_macro_teams');        
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function division()
    {
        return $this->belongsTo(\App\Models\Division::class, 'id_division');
    }
    
}
