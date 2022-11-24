<?php

namespace App\Repositories;

use App\Models\Edition;
use App\Repositories\BaseRepository;

/**
 * Class EditionRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:15 pm UTC
*/

class EditionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'edition_name',
        'edition_type',
        'edition_description',                
        'edition_rules', 
        'edition_zone_rules', 
        'edition_awards', 
        'edition_zones_and_clubs', 
        'logo',
        'subscription_fee'
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
        return Edition::class;
    }
}
