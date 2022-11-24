<?php

namespace App\Repositories;

use App\Models\Bracket;
use App\Repositories\BaseRepository;

/**
 * Class BracketRepository
 * @package App\Repositories
 * @version April 28, 2019, 9:43 am UTC
*/

class BracketRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
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
        return Bracket::class;
    }
}
