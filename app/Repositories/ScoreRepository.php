<?php

namespace App\Repositories;

use App\Models\Score;
use App\Repositories\BaseRepository;

/**
 * Class ScoreRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:23 pm UTC
*/

class ScoreRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_team',
        'set',
        'points'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Score::class;
    }
}
