<?php


namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Matchcode extends Model
{
    //use SoftDeletes;

    public $table = 'matchcodes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_ref',
        'ref_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_ref' => 'integer',
        'ref_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_ref' => 'required',
        'ref_type' => 'required'
    ];

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function matches()
    {
        return $this->hasMany(\App\Models\Match::class, 'matchcode', 'id', 'matches');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function macro_matches()
    {
        return $this->hasMany(\App\Models\MacroMatch::class, 'matchcode', 'id', 'macro_matches');
    }

    public function matchRound()
    {
        return $this->belongsTo(\App\Models\Round::class, 'id_ref', 'id', 'rounds');
    }

    public function matchPhase()
    {
        return $this->belongsTo(\App\Models\Phase::class, 'id_ref', 'id', 'phases');
    }

}
