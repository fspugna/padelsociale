<?php

namespace App\Repositories;

use App\Models\EditionClub;
use App\Repositories\BaseRepository;

/**
 * Class EditionClubRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:20 pm UTC
*/

class EditionClubRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_club'
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
        return EditionClub::class;
    }
}
