<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Match
 * @package App\Models
 * @version April 15, 2019, 7:22 pm UTC
 *
 * @property \App\Models\Round idRound
 * @property \App\Models\Team idTeam1
 * @property \App\Models\Team idTeam2
 * @property \Illuminate\Database\Eloquent\Collection teams
 * @property integer id_round
 * @property integer id_team1
 * @property integer id_team2
 * @property string date
 * @property time time
 */
class Match extends Model
{
    //use SoftDeletes;

    public $table = 'matches';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'matchcode',
        'id_team1',
        'id_team2',
        'date',
        'time',
        'prev_matchcode',
        'next_matchcode',
        'id_user',
        'a_tavolino',
        'note',
        'id_macro_match',
        'match_order',
        'pitch'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'matchcode' => 'integer',
        'id_team1' => 'integer',
        'id_team2' => 'integer',
        'date' => 'date',
        'prev_matchcode' => 'integer',
        'next_matchcode' => 'integer',
        'id_user' => 'integer',
        'a_tavolino' => 'integer',
        'note' => 'string',
        'id_macro_match' => 'integer',
        'match_order' => 'integer',
        'pitch' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'matchcode' => 'required',
        'id_team1' => 'required',
        'id_team2' => 'required',
        'date' => 'required',
        'time' => 'required',
        'id_user' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function matchcodes()
    {
        return $this->belongsTo(\App\Models\Matchcode::class, 'matchcode', 'id', 'matchcodes');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function team1()
    {
        return $this->belongsTo(\App\Models\Team::class, 'id_team1', 'id', 'teams');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function team2()
    {
        return $this->belongsTo(\App\Models\Team::class, 'id_team2', 'id', 'teams');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function scores()
    {
        return $this->hasMany(\App\Models\Score::class, 'id_match', 'id', 'scores');
    }

    public function club()
    {
        return $this->belongsTo(\App\Models\Club::class, 'id_club', 'id', 'clubs');
    }

    public function macroMatch()
    {
        return $this->belongsTo(\App\Models\MacroMatch::class, 'id_macro_match', 'id', 'macro_matches');
    }

}
