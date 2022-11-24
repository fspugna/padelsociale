<?php

namespace App\Repositories;

use App\Models\Classification;
use App\Repositories\BaseRepository;

/**
 * Class ClassificationRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:23 pm UTC
*/

class ClassificationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_team',
        'points',
        'played',
        'won',
        'set_won',
        'set_lost',
        'games_won',
        'games_lost'
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
        return Classification::class;
    }
}
