<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class News
 * @package App\Models
 * @version April 15, 2019, 7:23 pm UTC
 *
 * @property \App\Models\Image idImage
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property string title
 * @property string excerpt
 * @property string content
 * @property integer id_image
 * @property integer like
 * @property boolean status
 */
class News extends Model
{
    //use SoftDeletes;

    public $table = 'news';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_news_category',
        'title',        
        'excerpt',
        'content',
        'image',
        'like',
        'status',
        'permalink',
        'notified'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_news_category' => 'integer',
        'title' => 'string',        
        'excerpt' => 'string',
        'content' => 'string',
        'image' => 'string',        
        'status' => 'boolean',
        'permalink' => 'string',
        'notified' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_news_category' => 'required',
        'title' => 'required',
        'excerpt' => 'required',
        'content' => 'required'        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\NewsCategory::class, 'id_news_category');
    }
}
