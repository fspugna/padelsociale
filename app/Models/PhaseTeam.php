<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
class PhaseTeam extends Model
{
    //use SoftDeletes;

    public $table = 'phase_teams';
    
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
    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'id_team');
    }
}
