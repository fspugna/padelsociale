<?php

namespace App\Repositories;

use App\Models\GroupMacroTeam;
use App\Repositories\BaseRepository;

/**
 * Class GroupTeamRepository
 * @package App\Repositories
 * @version May 14, 2019, 9:38 pm UTC
*/

class GroupMacroTeamRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_group',
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
        return GroupTeam::class;
    }
}
