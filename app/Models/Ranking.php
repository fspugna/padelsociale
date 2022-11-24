<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ranking
 * @package App\Models
 * @version April 15, 2019, 7:25 pm UTC
 *
 * @property \App\Models\Edition idEdition
 * @property \App\Models\User idPlayer
 * @property \Illuminate\Database\Eloquent\Collection
 * @property integer id_edition
 * @property integer id_player
 */
class Ranking extends Model
{
    //use SoftDeletes;

    public $table = 'rankings';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = "id";

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id_event',
        'id_edition',
        'id_player',
        'id_club',
        'id_city',
        'year',
        'points'
    ];

    protected $casts = [
        'id_event' => 'integer',
        'id_edition' => 'integer',
        'id_player' => 'integer',
        'id_club' => 'integer',
        'id_city' => 'integer',
        'year' => 'integer',
        'points' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_event' => 'required',
        'id_edition' => 'required',
        'id_player' => 'required',
        'id_club' => 'required',
        'id_city' => 'required',
        'year' => 'required',
        'points' => 'required'
    ];


    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class, 'id_event');
    }

    public function edition()
    {
        return $this->belongsTo(\App\Models\Edition::class, 'id_edition');
    }

    public function player()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_player');
    }

    public function club()
    {
        return $this->belongsTo(\App\Models\Club::class, 'id_club');
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'id_city');
    }
}
