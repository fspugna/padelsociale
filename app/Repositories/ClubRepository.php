<?php

namespace App\Repositories;

use App\Models\Club;
use App\Repositories\BaseRepository;

/**
 * Class ClubRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:19 pm UTC
*/

class ClubRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_club',
        'id_user',
        'name',
        'address',
        'city',
        'mobile_phone',
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
        return Club::class;
    }
}
