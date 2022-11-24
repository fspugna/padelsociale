<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateEditionTeamRequest;
use App\Http\Requests\UpdateEditionTeamRequest;
use App\Repositories\EditionTeamRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class EditionTeamController extends AppBaseController
{
    /** @var  EditionTeamRepository */
    private $editionTeamRepository;

    public function __construct(EditionTeamRepository $editionTeamRepo)
    {
        $this->editionTeamRepository = $editionTeamRepo;
    }

    /**
     * Display a listing of the EditionTeam.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $editionTeams = $this->editionTeamRepository->all();

        return view('admin.edition_teams.index')
            ->with('editionTeams', $editionTeams);
    }

    /**
     * Show the form for creating a new EditionTeam.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.edition_teams.create');
    }

    /**
     * Store a newly created EditionTeam in storage.
     *
     * @param CreateEditionTeamRequest $request
     *
     * @return Response
     */
    public function store(CreateEditionTeamRequest $request)
    {
        $input = $request->all();

        $editionTeam = $this->editionTeamRepository->create($input);

        Flash::success('Edition Team saved successfully.');

        return redirect(route('editionTeams.index'));
    }

    /**
     * Display the specified EditionTeam.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $editionTeam = $this->editionTeamRepository->find($id);

        if (empty($editionTeam)) {
            Flash::error('Edition Team not found');

            return redirect(route('editionTeams.index'));
        }

        return view('admin.edition_teams.show')->with('editionTeam', $editionTeam);
    }

    /**
     * Show the form for editing the specified EditionTeam.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $editionTeam = $this->editionTeamRepository->find($id);

        if (empty($editionTeam)) {
            Flash::error('Edition Team not found');

            return redirect(route('editionTeams.index'));
        }

        return view('admin.edition_teams.edit')->with('editionTeam', $editionTeam);
    }

    /**
     * Update the specified EditionTeam in storage.
     *
     * @param int $id
     * @param UpdateEditionTeamRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEditionTeamRequest $request)
    {
        $editionTeam = $this->editionTeamRepository->find($id);

        if (empty($editionTeam)) {
            Flash::error('Edition Team not found');

            return redirect(route('editionTeams.index'));
        }

        $editionTeam = $this->editionTeamRepository->update($request->all(), $id);

        Flash::success('Edition Team updated successfully.');

        return redirect(route('editionTeams.index'));
    }

    /**
     * Remove the specified EditionTeam from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $editionTeam = $this->editionTeamRepository->find($id);

        if (empty($editionTeam)) {
            Flash::error('Edition Team not found');

            return redirect(route('editionTeams.index'));
        }

        $this->editionTeamRepository->delete($id);

        Flash::success('Edition Team deleted successfully.');

        return redirect(route('editionTeams.index'));
    }
}
