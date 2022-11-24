<?php

namespace App\Repositories;

use App\Models\Image;
use App\Repositories\BaseRepository;

/**
 * Class ImageRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:14 pm UTC
*/

class ImageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'path',
        'label'
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
        return Image::class;
    }
}
