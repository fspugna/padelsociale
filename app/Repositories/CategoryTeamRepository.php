<?php

namespace App\Repositories;

use App\Models\CategoryTeam;
use App\Repositories\BaseRepository;

/**
 * Class CategoryTeamRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:22 pm UTC
*/

class CategoryTeamRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_category',
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
        return CategoryTeam::class;
    }
}
