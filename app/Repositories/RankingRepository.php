<?php

namespace App\Repositories;

use App\Models\Ranking;
use App\Repositories\BaseRepository;

/**
 * Class RankingRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:25 pm UTC
*/

class RankingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_edition',
        'id_player'
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
        return Ranking::class;
    }
}
