<?php

namespace App\Repositories;

use App\Models\TeamPlayer;
use App\Repositories\BaseRepository;

/**
 * Class TeamPlayerRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:20 pm UTC
*/

class TeamPlayerRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_team',
        'id_player',
        'starter'
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
        return TeamPlayer::class;
    }
}
