<?php

namespace App\Repositories;

use App\Models\Info;
use App\Repositories\BaseRepository;

/**
 * Class InfoRepository
 * @package App\Repositories
 * @version August 30, 2019, 7:00 pm UTC
*/

class InfoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'content',
        'image',
        'permalink',      
        'status'
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
        return Info::class;
    }
}
