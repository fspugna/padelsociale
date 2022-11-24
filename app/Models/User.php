<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use HasApiTokens, Notifiable;

    //use SoftDeletes;

    public $table = 'users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'surname',
        'email',
        'email_verified_at',
        'mobile_phone',
        'password',
        'id_role',
        'id_city',
        'status',
        'remember_token',
        'gender',
        'position',
        'id_club',
        'lun',
        'mar',
        'mer',
        'gio',
        'ven',
        'sab',
        'dom',
        'note_disp'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'surname' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'mobile_phone' => 'string',
        'password' => 'string',
        'id_role' => 'integer',
        'id_city' => 'integer',
        'status' => 'integer',
        'remember_token' => 'string',
        'gender' => 'string',
        'position' => 'integer',
        'id_club' => 'integer',
        'lun' => 'string',
        'mar' => 'string',
        'mer' => 'string',
        'gio' => 'string',
        'ven' => 'string',
        'sab' => 'string',
        'dom' => 'string',
        'note_disp' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'surname' => 'required',
        'id_role' => 'required',
        'id_city' => 'required',
        'status' => 'required',
        'gender' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class, 'id_role');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function clubs()
    {
        return $this->belongsTo(\App\Models\Club::class, 'id_club');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function rankings()
    {
        return $this->hasMany(\App\Models\Ranking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function teams()
    {
        return $this->belongsToMany(\App\Models\Team::class, 'team_players', 'id_player', 'id_team');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function metas()
    {
        return $this->hasMany(\App\Models\UserMeta::class, 'id_user');
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'id_city');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ownclub()
    {
        return $this->hasMany(\App\Models\Club::class, 'clubs', 'id_user', 'id');
    }

    public function partners()
    {
        return $this->hasMany(\App\Models\Partner::class, 'id_user', 'id', 'partners');
    }

    public function galleries()
    {
        return $this->hasMany(\App\Models\UserGallery::class, 'id_user');
    }
}
