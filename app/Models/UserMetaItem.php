<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserMeta
 * @package App\Models
 * @version April 15, 2019, 7:16 pm UTC
 *
 * @property \App\Models\User idUser
 * @property integer id_user
 * @property string meta
 * @property string meta_value
 */
class UserMetaItem extends Model
{
    //use SoftDeletes;

    public $table = 'user_meta_items';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_user',
        'meta_key',
        'meta_type',
        'meta_cat'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',        
        'meta_key' => 'string',
        'meta_type' => 'string',
        'meta_cat' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_user' => 'required',
        'meta_key' => 'required',
        'meta_type' => 'required',
        'meta_cat' => 'required'
    ];

    
}
