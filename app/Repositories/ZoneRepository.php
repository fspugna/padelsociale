<?php

namespace App\Repositories;

use App\Models\Zone;
use App\Repositories\BaseRepository;

/**
 * Class ZoneRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:16 pm UTC
*/

class ZoneRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'id_city'
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
        return Zone::class;
    }
}
