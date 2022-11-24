<?php

namespace App\Repositories;

use App\Models\GalleryImage;
use App\Repositories\BaseRepository;

/**
 * Class GalleryImageRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:24 pm UTC
*/

class GalleryImageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_image'
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
        return GalleryImage::class;
    }
}
