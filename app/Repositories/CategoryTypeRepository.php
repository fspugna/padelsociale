<?php

namespace App\Repositories;

use App\Models\CategoryType;
use App\Repositories\BaseRepository;

/**
 * Class CategoryTypeRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:21 pm UTC
*/

class CategoryTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return CategoryType::class;
    }
}
