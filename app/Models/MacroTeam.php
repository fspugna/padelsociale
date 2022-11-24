<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MacroTeam
 * @package App\Models
 * @version April 15, 2019, 7:19 pm UTC
 *
 * @property \App\Models\Zone idZone
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property \Illuminate\Database\Eloquent\Collection categories
 * @property \Illuminate\Database\Eloquent\Collection rounds
 * @property \Illuminate\Database\Eloquent\Collection editions
 * @property \Illuminate\Database\Eloquent\Collection matches
 * @property \Illuminate\Database\Eloquent\Collection users
 * @property integer id_zone
 * @property string name
 * @property string gender
 */
class MacroTeam extends Model
{
    //use SoftDeletes;

    public $table = 'macro_teams';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [        
        'name',
        'flag_change',
        'info_match_home',
        'captain',
        'tel_captain'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',        
        'name' => 'string',
        'id_club' => 'integer',
        'flag_change' => 'boolean',
        'info_match_home' => 'string',
        'captain' => 'string',
        'tel_captain' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [        
    ];

    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'macro_team_players', 'id_player', 'user_id');
    }

    public function players(){
        return $this->hasMany(\App\Models\MacroTeamPlayer::class, 'id_team');
    }

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\MacroSubscription::class, 'id_team');
    }
    
    public function club(){
        return $this->belongsTo(\App\Models\Club::class, 'id_club');
    }
    
}
