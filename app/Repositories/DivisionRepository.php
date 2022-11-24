<?php

namespace App\Repositories;

use App\Models\Division;
use App\Repositories\BaseRepository;

/**
 * Class DivisionRepository
 * @package App\Repositories
 * @version April 28, 2019, 9:43 am UTC
*/

class DivisionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_tournament',
        'id_zone',
        'id_category',
        'id_category_type'
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
        return Division::class;
    }
}
