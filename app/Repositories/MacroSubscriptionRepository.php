<?php

namespace App\Repositories;

use App\Models\MacroSubscription;
use App\Repositories\BaseRepository;

/**
 * Class SubscriptionRepository
 * @package App\Repositories
 * @version April 28, 2019, 9:43 am UTC
*/

class MacroSubscriptionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_team',
        'id_tournament',
        'id_zone',
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
        return MacroSubscription::class;
    }
}
