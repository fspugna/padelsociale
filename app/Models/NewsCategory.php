<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NewsCategory
 * @package App\Models
 * @version August 30, 2019, 9:30 pm UTC
 *
 * @property string name
 */
class NewsCategory extends Model
{
    //use SoftDeletes;

    public $table = 'news_categories';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'slug'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',        
    ];


    public function news(){
        return $this->hasMany(\App\Models\News::class, 'id_news_category', 'id', 'news');
    }
    
}
