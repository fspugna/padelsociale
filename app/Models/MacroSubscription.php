<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Subscription
 * @package App\Models
 * @version April 28, 2019, 9:43 am UTC
 *
 * @property \App\Models\CategoryType idCategoryType
 * @property \App\Models\Team idTeam
 * @property \App\Models\Zone idZone
 * @property integer id_team
 * @property integer id_tournament
 * @property integer id_zone
 * @property integer id_category_type
 */
class MacroSubscription extends Model
{
    //use SoftDeletes;

    public $table = 'macro_subscriptions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id_team',
        'id_tournament',
        'id_zone',
        'id_category_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_team' => 'integer',
        'id_tournament' => 'integer',
        'id_zone' => 'integer',
        'id_category_type' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_team' => 'required',
        'id_tournament' => 'required',
        'id_zone' => 'required',
        'id_category_type' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tournament()
    {
        return $this->belongsTo(\App\Models\Tournament::class, 'id_tournament');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'id_category');
    }

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
    public function team()
    {
        return $this->belongsTo(\App\Models\MacroTeam::class, 'id_team');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function zone()
    {
        return $this->belongsTo(\App\Models\Zone::class, 'id_zone');
    }
}
