<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MacroMatch extends Model
{
    //use SoftDeletes;

    public $table = 'macro_matches';
    
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
        'jolly_team1',
        'jolly_team2'
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
        'jolly_team1' => 'integer',
        'jolly_team2' => 'integer'
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
        return $this->belongsTo(\App\Models\MacroTeam::class, 'id_team1', 'id', 'teams');
    }    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function team2()
    {
        return $this->belongsTo(\App\Models\MacroTeam::class, 'id_team2', 'id', 'teams');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function macro_scores()
    {
        return $this->hasMany(\App\Models\MacroScore::class, 'id_match', 'id', 'scores');
    }

    public function club()
    {        
        return $this->belongsTo(\App\Models\Club::class, 'id_club', 'id', 'clubs');        
    }    
    
    public function subMatches()
    {
        return $this->hasMany(\App\Models\Match::class, 'id_macro_match', 'id', 'matches');        
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function jolly1()
    {
        return $this->belongsTo(\App\Models\User::class, 'jolly_team1', 'id', 'users');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function jolly2()
    {
        return $this->belongsTo(\App\Models\User::class, 'jolly_team2', 'id', 'users');
    }
}
