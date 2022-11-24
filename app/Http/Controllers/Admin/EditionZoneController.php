<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateEditionZoneRequest;
use App\Http\Requests\UpdateEditionZoneRequest;
use App\Repositories\EditionZoneRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class EditionZoneController extends AppBaseController
{
    /** @var  EditionZoneRepository */
    private $editionZoneRepository;

    public function __construct(EditionZoneRepository $editionZoneRepo)
    {
        $this->editionZoneRepository = $editionZoneRepo;
    }

    /**
     * Display a listing of the EditionZone.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $editionZones = $this->editionZoneRepository->all();

        return view('edition_zones.index')
            ->with('editionZones', $editionZones);
    }

    /**
     * Show the form for creating a new EditionZone.
     *
     * @return Response
     */
    public function create()
    {
        return view('edition_zones.create');
    }

    /**
     * Store a newly created EditionZone in storage.
     *
     * @param CreateEditionZoneRequest $request
     *
     * @return Response
     */
    public function store(CreateEditionZoneRequest $request)
    {
        $input = $request->all();

        $editionZone = $this->editionZoneRepository->create($input);

        Flash::success('Edition Zone saved successfully.');

        return redirect(route('editionZones.index'));
    }

    /**
     * Display the specified EditionZone.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $editionZone = $this->editionZoneRepository->find($id);

        if (empty($editionZone)) {
            Flash::error('Edition Zone not found');

            return redirect(route('editionZones.index'));
        }

        return view('edition_zones.show')->with('editionZone', $editionZone);
    }

    /**
     * Show the form for editing the specified EditionZone.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $editionZone = $this->editionZoneRepository->find($id);

        if (empty($editionZone)) {
            Flash::error('Edition Zone not found');

            return redirect(route('editionZones.index'));
        }

        return view('edition_zones.edit')->with('editionZone', $editionZone);
    }

    /**
     * Update the specified EditionZone in storage.
     *
     * @param int $id
     * @param UpdateEditionZoneRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEditionZoneRequest $request)
    {
        $editionZone = $this->editionZoneRepository->find($id);

        if (empty($editionZone)) {
            Flash::error('Edition Zone not found');

            return redirect(route('editionZones.index'));
        }

        $editionZone = $this->editionZoneRepository->update($request->all(), $id);

        Flash::success('Edition Zone updated successfully.');

        return redirect(route('editionZones.index'));
    }

    /**
     * Remove the specified EditionZone from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $editionZone = $this->editionZoneRepository->find($id);

        if (empty($editionZone)) {
            Flash::error('Edition Zone not found');

            return redirect(route('editionZones.index'));
        }

        $this->editionZoneRepository->delete($id);

        Flash::success('Edition Zone deleted successfully.');

        return redirect(route('editionZones.index'));
    }
}
