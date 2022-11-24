<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGroupTeamRequest;
use App\Http\Requests\UpdateGroupTeamRequest;
use App\Repositories\GroupTeamRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class GroupTeamController extends AppBaseController
{
    /** @var  GroupTeamRepository */
    private $groupTeamRepository;

    public function __construct(GroupTeamRepository $groupTeamRepo)
    {
        $this->groupTeamRepository = $groupTeamRepo;
    }

    /**
     * Display a listing of the GroupTeam.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $groupTeams = $this->groupTeamRepository->all();

        return view('group_teams.index')
            ->with('groupTeams', $groupTeams);
    }

    /**
     * Show the form for creating a new GroupTeam.
     *
     * @return Response
     */
    public function create()
    {
        return view('group_teams.create');
    }

    /**
     * Store a newly created GroupTeam in storage.
     *
     * @param CreateGroupTeamRequest $request
     *
     * @return Response
     */
    public function store(CreateGroupTeamRequest $request)
    {
        $input = $request->all();

        $groupTeam = $this->groupTeamRepository->create($input);

        Flash::success('Group Team saved successfully.');

        return redirect(route('groupTeams.index'));
    }

    /**
     * Display the specified GroupTeam.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $groupTeam = $this->groupTeamRepository->find($id);

        if (empty($groupTeam)) {
            Flash::error('Group Team not found');

            return redirect(route('groupTeams.index'));
        }

        return view('group_teams.show')->with('groupTeam', $groupTeam);
    }

    /**
     * Show the form for editing the specified GroupTeam.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $groupTeam = $this->groupTeamRepository->find($id);

        if (empty($groupTeam)) {
            Flash::error('Group Team not found');

            return redirect(route('groupTeams.index'));
        }

        return view('group_teams.edit')->with('groupTeam', $groupTeam);
    }

    /**
     * Update the specified GroupTeam in storage.
     *
     * @param int $id
     * @param UpdateGroupTeamRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGroupTeamRequest $request)
    {
        $groupTeam = $this->groupTeamRepository->find($id);

        if (empty($groupTeam)) {
            Flash::error('Group Team not found');

            return redirect(route('groupTeams.index'));
        }

        $groupTeam = $this->groupTeamRepository->update($request->all(), $id);

        Flash::success('Group Team updated successfully.');

        return redirect(route('groupTeams.index'));
    }

    /**
     * Remove the specified GroupTeam from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $groupTeam = $this->groupTeamRepository->find($id);

        if (empty($groupTeam)) {
            Flash::error('Group Team not found');

            return redirect(route('groupTeams.index'));
        }

        $this->groupTeamRepository->delete($id);

        Flash::success('Group Team deleted successfully.');

        return redirect(route('groupTeams.index'));
    }
}
