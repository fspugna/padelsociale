<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Score
 * @package App\Models
 * @version April 15, 2019, 7:23 pm UTC
 *
 * @property \App\Models\Match idMatch
 * @property \App\Models\Team idTeam
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property integer id_team
 * @property string set
 * @property boolean points
 */
class Score extends Model {

    //use SoftDeletes;

    public $table = 'scores';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = ['id_match', 'id_team', 'set'];
    public $incrementing = false;
    protected $dates = ['deleted_at'];
    public $fillable = [
        'id_team',
        'set',
        'points'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_match' => 'integer',
        'id_team' => 'integer',
        'set' => 'string',
        'points' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id_match' => 'required',
        'id_team' => 'required',
        'set' => 'required',
        'points' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function match() {
        return $this->belongsTo(\App\Models\Match::class, 'id_match');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * */
    public function team() {
        return $this->belongsTo(\App\Models\Team::class, 'id_team');
    }

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query) {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null) {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

}
