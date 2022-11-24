<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateTournamentTypeRequest;
use App\Http\Requests\UpdateTournamentTypeRequest;
use App\Repositories\TournamentTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TournamentTypeController extends AppBaseController
{
    /** @var  TournamentTypeRepository */
    private $tournamentTypeRepository;

    public function __construct(TournamentTypeRepository $tournamentTypeRepo)
    {
        $this->tournamentTypeRepository = $tournamentTypeRepo;
    }

    /**
     * Display a listing of the TournamentType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tournamentTypes = $this->tournamentTypeRepository->all();

        return view('admin.tournamentTypes.index')
            ->with('tournamentTypes', $tournamentTypes);
    }

    /**
     * Show the form for creating a new TournamentType.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.tournamentTypes.create');
    }

    /**
     * Store a newly created TournamentType in storage.
     *
     * @param CreateTournamentTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateTournamentTypeRequest $request)
    {
        $input = $request->all();

        $tournamentType = $this->tournamentTypeRepository->create($input);

        Flash::success('Tournament Type saved successfully.');

        return redirect(route('admin.tournamentTypes.index'));
    }

    /**
     * Display the specified TournamentType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tournamentType = $this->tournamentTypeRepository->find($id);

        if (empty($tournamentType)) {
            Flash::error('Tournament Type not found');

            return redirect(route('admin.tournamentTypes.index'));
        }

        return view('admin.tournamentTypes.show')->with('tournamentType', $tournamentType);
    }

    /**
     * Show the form for editing the specified TournamentType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tournamentType = $this->tournamentTypeRepository->find($id);

        if (empty($tournamentType)) {
            Flash::error('Tournament Type not found');

            return redirect(route('admin.tournamentTypes.index'));
        }

        return view('admin.tournamentTypes.edit')->with('tournamentType', $tournamentType);
    }

    /**
     * Update the specified TournamentType in storage.
     *
     * @param int $id
     * @param UpdateTournamentTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTournamentTypeRequest $request)
    {
        $tournamentType = $this->tournamentTypeRepository->find($id);

        if (empty($tournamentType)) {
            Flash::error('Tournament Type not found');

            return redirect(route('admin.tournamentTypes.index'));
        }

        $tournamentType = $this->tournamentTypeRepository->update($request->all(), $id);

        Flash::success('Tournament Type updated successfully.');

        return redirect(route('admin.tournamentTypes.index'));
    }

    /**
     * Remove the specified TournamentType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tournamentType = $this->tournamentTypeRepository->find($id);

        if (empty($tournamentType)) {
            Flash::error('Tournament Type not found');

            return redirect(route('admin.tournamentTypes.index'));
        }

        $this->tournamentTypeRepository->delete($id);

        Flash::success('Tournament Type deleted successfully.');

        return redirect(route('admin.tournamentTypes.index'));
    }
}
