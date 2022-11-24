<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Team
 * @package App\Models
 * @version April 15, 2019, 7:19 pm UTC
 *
 * @property \App\Models\Zone idZone
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property \Illuminate\Database\Eloquent\Collection categories
 * @property \Illuminate\Database\Eloquent\Collection rounds
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property \Illuminate\Database\Eloquent\Collection editions
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property \Illuminate\Database\Eloquent\Collection matches
 * @property \Illuminate\Database\Eloquent\Collection matches
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property \Illuminate\Database\Eloquent\Collection matches
 * @property \Illuminate\Database\Eloquent\Collection users
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property integer id_zone
 * @property string name
 * @property string gender
 */
class Team extends Model
{
    //use SoftDeletes;

    public $table = 'teams';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [        
        'name',
        'flag_change'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',        
        'name' => 'string',
        'flag_change' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function zone()
    {
        return $this->belongsTo(\App\Models\Zone::class, 'id_zone');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'categories_teams');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function rounds()
    {
        return $this->belongsToMany(\App\Models\Round::class, 'classifications');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function matches()
    {
        return $this->hasMany(\App\Models\Match::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'team_players', 'id_player', 'user_id');
    }

    public function players(){
        return $this->hasMany(\App\Models\TeamPlayer::class, 'id_team');
    }

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class, 'id_team');
    }
    
}
