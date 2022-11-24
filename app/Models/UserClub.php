<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App\Models
 * @version April 15, 2019, 9:12 pm UTC
 *
 * @property \App\Models\Role idRole
 * @property \Illuminate\Database\Eloquent\Collection clubs 
 * @property \Illuminate\Database\Eloquent\Collection rankings 
 * @property \Illuminate\Database\Eloquent\Collection teams 
 * @property \Illuminate\Database\Eloquent\Collection userMetas
 * @property string name
 * @property string email
 * @property string|\Carbon\Carbon email_verified_at
 * @property string password
 * @property integer id_role
 * @property string remember_token
 */
class UserClub extends Model
{

    use HasApiTokens, Notifiable;
    
    //use SoftDeletes;

    public $table = 'user_clubs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_user' => 'integer',        
        'id_club' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_user' => 'required',        
        'id_club' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function club()
    {
        return $this->belongsTo(\App\Models\Club::class, 'id_club');
    }
    
}
