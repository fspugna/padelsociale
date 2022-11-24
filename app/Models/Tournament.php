<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tournament
 * @package App\Models
 * @version April 15, 2019, 7:16 pm UTC
 *
 * @property \App\Models\Edition idEdition
 * @property \App\Models\TournamentType idTournamentType
 * @property \Illuminate\Database\Eloquent\Collection categories
 * @property integer id_edition
 * @property integer id_tournament_type
 * @property string name
 * @property string date_start
 * @property string date_end
 * @property string description
 */
class Tournament extends Model
{
    //use SoftDeletes;

    public $table = 'tournaments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_edition',
        'id_tournament_type',
        'id_tournament_ref',
        'name',
        'date_start',
        'date_end',
        'registration_deadline_date',
        'description',
        'generated'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_edition' => 'integer',        
        'id_tournament_type' => 'integer',
        'id_tournament_ref' => 'integer',
        'name' => 'string',
        'date_start' => 'date',
        'date_end' => 'date',
        'registration_deadline_date' => 'date',
        'description' => 'string',
        'generated' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_edition' => 'required',        
        'id_tournament_type' => 'required',                
        'date_start' => 'required',
        'date_end' => 'required'        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function edition()
    {
        return $this->belongsTo(\App\Models\Edition::class, 'id_edition');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function divisions()
    {
        return $this->hasMany(\App\Models\Division::class, 'id_tournament');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function brackets()
    {
        return $this->hasMany(\App\Models\Bracket::class, 'id_tournament');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tournamentType()
    {
        return $this->belongsTo(\App\Models\TournamentType::class, 'id_tournament_type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tournamentRef()
    {
        return $this->belongsTo(\App\Models\Tournament::class, 'id_tournament_ref');
    }
}
