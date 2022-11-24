<?php

namespace App\Repositories;

use App\Models\NewsCategory;
use App\Repositories\BaseRepository;

/**
 * Class NewsCategoryRepository
 * @package App\Repositories
 * @version August 30, 2019, 9:30 pm UTC
*/

class NewsCategoryRepository extends BaseRepository
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
        return NewsCategory::class;
    }
}
