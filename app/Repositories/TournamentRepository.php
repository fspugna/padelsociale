<?php

namespace App\Repositories;

use App\Models\Tournament;
use App\Repositories\BaseRepository;

/**
 * Class TournamentRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:16 pm UTC
*/

class TournamentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_edition',
        'id_tournament_type',
        'name',
        'date_start',
        'date_end',
        'description'
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
        return Tournament::class;
    }
}
