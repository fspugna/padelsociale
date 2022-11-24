<?php

namespace App\Repositories;

use App\Models\Round;
use App\Repositories\BaseRepository;

/**
 * Class RoundRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:22 pm UTC
*/

class RoundRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_group',
        'round'
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
        return Round::class;
    }
}
