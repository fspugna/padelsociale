<?php

namespace App\Repositories;

use App\Models\UserMeta;
use App\Repositories\BaseRepository;

/**
 * Class UserMetaRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:16 pm UTC
*/

class UserMetaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_user',
        'meta',
        'meta_value'
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
        return UserMeta::class;
    }
}
