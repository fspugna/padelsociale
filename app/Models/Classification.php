<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Classification
 * @package App\Models
 * @version April 15, 2019, 7:23 pm UTC
 *
 * @property \App\Models\Round idRound
 * @property \App\Models\Team idTeam
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property integer id_team
 * @property boolean points
 * @property boolean played
 * @property boolean won
 * @property boolean set_won
 * @property boolean set_lost
 * @property boolean games_won
 * @property boolean games_lost
 */
class Classification extends Model
{
    //use SoftDeletes;

    public $table = 'classifications';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_match',
        'id_team',
        'points',
        'played',
        'won',
        'lost',
        'draws',
        'set_won',
        'set_lost',
        'games_won',
        'games_lost'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_match' => 'integer',        
        'id_team' => 'integer',
        'points' => 'integer',
        'played' => 'integer',
        'won' => 'integer',
        'lost' => 'integer',
        'draws' => 'integer',
        'set_won' => 'integer',
        'set_lost' => 'integer',
        'games_won' => 'integer',
        'games_lost' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_match' => 'required',
        'id_team' => 'required',
        'points' => 'required',
        'played' => 'required',
        'won' => 'required',
        'lost' => 'required',
        'draws' => 'required',
        'set_won' => 'required',
        'set_lost' => 'required',
        'games_won' => 'required',
        'games_lost' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function rounds()
    {
        return $this->belongsTo(\App\Models\Round::class, 'id_round');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function teams()
    {
        return $this->belongsTo(\App\Models\Team::class, 'id_team');
    }


    public function group()
    {
        return $this->belongsTo(\App\Models\Group::class, 'id_group');
    }

    public function match()
    {
        return $this->belongsTo(\App\Models\Match::class, 'id_match');
    }
}
