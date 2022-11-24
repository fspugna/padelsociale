<?php

namespace App\Repositories;

use App\Models\TournamentType;
use App\Repositories\BaseRepository;

/**
 * Class TournamentTypeRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:15 pm UTC
*/

class TournamentTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tournament_type'
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
        return TournamentType::class;
    }
}
