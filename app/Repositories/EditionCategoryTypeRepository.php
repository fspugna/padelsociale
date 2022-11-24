<?php

namespace App\Repositories;

use App\Models\EditionCategoryType;
use App\Repositories\BaseRepository;

/**
 * Class EditionCategoryTypeRepository
 * @package App\Repositories
 * @version April 26, 2019, 11:25 am UTC
*/

class EditionCategoryTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        return EditionCategoryType::class;
    }
}
