<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateEditionClubRequest;
use App\Http\Requests\UpdateEditionClubRequest;
use App\Repositories\EditionClubRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class EditionClubController extends AppBaseController
{
    /** @var  EditionClubRepository */
    private $editionClubRepository;

    public function __construct(EditionClubRepository $editionClubRepo)
    {
        $this->editionClubRepository = $editionClubRepo;
    }

    /**
     * Display a listing of the EditionClub.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $editionClubs = $this->editionClubRepository->all();

        return view('admin.edition_clubs.index')
            ->with('editionClubs', $editionClubs);
    }

    /**
     * Show the form for creating a new EditionClub.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.edition_clubs.create');
    }

    /**
     * Store a newly created EditionClub in storage.
     *
     * @param CreateEditionClubRequest $request
     *
     * @return Response
     */
    public function store(CreateEditionClubRequest $request)
    {
        $input = $request->all();

        $editionClub = $this->editionClubRepository->create($input);

        Flash::success('Edition Club saved successfully.');

        return redirect(route('editionClubs.index'));
    }

    /**
     * Display the specified EditionClub.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $editionClub = $this->editionClubRepository->find($id);

        if (empty($editionClub)) {
            Flash::error('Edition Club not found');

            return redirect(route('editionClubs.index'));
        }

        return view('admin.edition_clubs.show')->with('editionClub', $editionClub);
    }

    /**
     * Show the form for editing the specified EditionClub.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $editionClub = $this->editionClubRepository->find($id);

        if (empty($editionClub)) {
            Flash::error('Edition Club not found');

            return redirect(route('editionClubs.index'));
        }

        return view('admin.edition_clubs.edit')->with('editionClub', $editionClub);
    }

    /**
     * Update the specified EditionClub in storage.
     *
     * @param int $id
     * @param UpdateEditionClubRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEditionClubRequest $request)
    {
        $editionClub = $this->editionClubRepository->find($id);

        if (empty($editionClub)) {
            Flash::error('Edition Club not found');

            return redirect(route('editionClubs.index'));
        }

        $editionClub = $this->editionClubRepository->update($request->all(), $id);

        Flash::success('Edition Club updated successfully.');

        return redirect(route('editionClubs.index'));
    }

    /**
     * Remove the specified EditionClub from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $editionClub = $this->editionClubRepository->find($id);

        if (empty($editionClub)) {
            Flash::error('Edition Club not found');

            return redirect(route('editionClubs.index'));
        }

        $this->editionClubRepository->delete($id);

        Flash::success('Edition Club deleted successfully.');

        return redirect(route('editionClubs.index'));
    }
}
