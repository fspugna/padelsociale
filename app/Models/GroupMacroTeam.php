<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class GroupTeam
 * @package App\Models
 * @version May 14, 2019, 9:38 pm UTC
 *
 * @property \App\Models\Group idGroup
 * @property \App\Models\Team idTeam
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property integer id_group
 * @property integer id_team
 */
class GroupMacroTeam extends Model
{
    //use SoftDeletes;

    public $table = 'group_macro_teams';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
    
    public $fillable = [
        'id_group',
        'id_team'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_group' => 'integer',
        'id_team' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_group' => 'required',
        'id_team' => 'required'
    ];
    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function group()
    {
        return $this->belongsTo(\App\Models\Group::class, 'id_group');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function macroTeam()
    {
        return $this->belongsTo(\App\Models\MacroTeam::class, 'id_team');
    }
}
