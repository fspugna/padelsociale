<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Image
 * @package App\Models
 * @version April 15, 2019, 7:14 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection editions
 * @property \Illuminate\Database\Eloquent\Collection galleries
 * @property \Illuminate\Database\Eloquent\Collection new
 * @property string path
 * @property string label
 */
class Image extends Model
{
    //use SoftDeletes;

    public $table = 'images';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'path',
        'label',
        'id_user',
        'id_match'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'path' => 'string',
        'label' => 'string',
        'id_user' => 'id_user',
        'id_match' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'path' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function editions()
    {
        return $this->hasMany(\App\Models\Edition::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function galleries()
    {
        return $this->belongsToMany(\App\Models\Gallery::class, 'gallery_images');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function news()
    {
        return $this->hasMany(\App\Models\News::class);
    }
}
