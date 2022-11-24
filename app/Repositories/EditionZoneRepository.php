<?php

namespace App\Repositories;

use App\Models\EditionZone;
use App\Repositories\BaseRepository;

/**
 * Class EditionZoneRepository
 * @package App\Repositories
 * @version April 24, 2019, 7:11 pm UTC
*/

class EditionZoneRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_zone'
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
        return EditionZone::class;
    }
}
