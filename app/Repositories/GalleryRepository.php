<?php

namespace App\Repositories;

use App\Models\Gallery;
use App\Repositories\BaseRepository;

/**
 * Class GalleryRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:24 pm UTC
*/

class GalleryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
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
        return Gallery::class;
    }
}
