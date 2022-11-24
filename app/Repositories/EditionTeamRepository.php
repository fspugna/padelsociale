<?php

namespace App\Repositories;

use App\Models\EditionTeam;
use App\Repositories\BaseRepository;

/**
 * Class EditionTeamRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:20 pm UTC
*/

class EditionTeamRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_team'
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
        return EditionTeam::class;
    }
}
