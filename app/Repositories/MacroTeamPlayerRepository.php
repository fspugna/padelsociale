<?php

namespace App\Repositories;

use App\Models\MacroTeamPlayer;
use App\Repositories\BaseRepository;

/**
 * Class TeamPlayerRepository
 * @package App\Repositories
 * @version April 15, 2019, 7:20 pm UTC
*/

class MacroTeamPlayerRepository extends BaseRepository
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
        return MacroTeamPlayer::class;
    }
}
