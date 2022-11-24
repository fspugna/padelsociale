<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TeamPlayer
 * @package App\Models
 * @version April 15, 2019, 7:20 pm UTC
 *
 * @property \App\Models\User idPlayer
 * @property \App\Models\Team idTeam
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property integer id_team
 * @property integer id_player
 * @property boolean starter
 */
class MacroTeamPlayer extends Model
{
    //use SoftDeletes;

    public $table = 'macro_team_players';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_team',
        'id_player',
        'starter'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_team' => 'integer',
        'id_player' => 'integer',
        'starter' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_team' => 'required',
        'id_player' => 'required',
        'starter' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function player()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_player', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function team()
    {
        return $this->belongsTo(\App\Models\MacroTeam::class, 'id_team');
    }
}
