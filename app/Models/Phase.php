<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phase extends Model
{
    public $table = 'phases';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id_bracket',
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
        'id_bracket' => 'integer',
        'name' => 'integer',
        'matchcode' => 'integer',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_bracket' => 'required',
        'name' => 'required',
        'matchcode' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function bracket()
    {
        return $this->belongsTo(\App\Models\Bracket::class, 'id_bracket');
    }
    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function matchcodes()
    {
        return $this->belongsTo(\App\Models\Matchcode::class, 'matchcode', 'id' , 'matchcodes');
    }

    
    public function teams(){
        return $this->hasMany(\App\Models\PhaseTeam::class, 'id_phase', 'id', 'phase_teams');
    }

    public function macro_teams(){
        return $this->hasMany(\App\Models\PhaseMacroTeam::class, 'id_phase', 'id', 'phase_macro_teams');
    }


    public function matches()
    {
        return $this->hasMany(\App\Models\Match::class, 'matchcode', 'matchcode' , 'matches');
    }
    
    public function macro_matches()
    {
        return $this->hasMany(\App\Models\MacroMatch::class, 'matchcode', 'matchcode' , 'matches');
    }
}
