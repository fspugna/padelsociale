<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhaseMacroTeam extends Model
{
    //use SoftDeletes;

    public $table = 'phase_macro_teams';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_phase',
        'id_team'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_phase' => 'integer',
        'id_team' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_phase' => 'required',
        'id_team' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function phase()
    {
        return $this->belongsTo(\App\Models\Phase::class, 'id_phase');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function macroTeam()
    {
        return $this->belongsTo(\App\Models\MacroTeam::class, 'id_team');
    }
}
