<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Info
 * @package App\Models
 * @version August 30, 2019, 7:00 pm UTC
 * @property string title
 * @property string content
 * @property string permalink
 * @property boolean notified
 * @property boolean status
 */
class Info extends Model
{
    //use SoftDeletes;

    public $table = 'infos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    
    public $fillable = [
        'title',
        'content',
        'image',
        'permalink',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'content' => 'string',
        'image' => 'string',
        'permalink' => 'string',
        'status' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'content' => 'required'        
    ];

    
}
