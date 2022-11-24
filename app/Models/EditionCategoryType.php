<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EditionCategoryType
 * @package App\Models
 * @version April 26, 2019, 11:25 am UTC
 *
 * @property \App\Models\CategoryType idCategoryType
 * @property \App\Models\Edition idEdition
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property integer id_category_type
 */
class EditionCategoryType extends Model
{
    //use SoftDeletes;

    public $table = 'edition_category_types';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_category_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_edition' => 'integer',
        'id_category_type' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_edition' => 'required',
        'id_category_type' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function categoryType()
    {
        return $this->belongsTo(\App\Models\CategoryType::class, 'id_category_type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function edition()
    {
        return $this->belongsTo(\App\Models\Edition::class, 'id_edition');
    }
}
