<?php

namespace App\Repositories;

use App\Models\EditionCategory;
use App\Repositories\BaseRepository;

/**
 * Class EditionCategoryRepository
 * @package App\Repositories
 * @version April 25, 2019, 4:19 pm UTC
*/

class EditionCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_category'
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
        return EditionCategory::class;
    }
}
