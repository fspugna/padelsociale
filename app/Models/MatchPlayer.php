<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchPlayer extends Model
{
    public $table = 'match_players';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id_match',
        'id_team',
        'side',
        'id_player'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_match' => 'integer',
        'id_team' => 'integer',        
        'side' => 'string',
        'id_player' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_match' => 'required',
        'id_team' => 'required',        
        'side' => 'required',
        'id_player' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function match()
    {
        return $this->belongsTo(\App\Models\Match::class, 'id_match');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'id_team');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function player()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_player');
    }
    
}
