<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateEditionCategoryTypeRequest;
use App\Http\Requests\UpdateEditionCategoryTypeRequest;
use App\Repositories\EditionCategoryTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class EditionCategoryTypeController extends AppBaseController
{
    /** @var  EditionCategoryTypeRepository */
    private $editionCategoryTypeRepository;

    public function __construct(EditionCategoryTypeRepository $editionCategoryTypeRepo)
    {
        $this->editionCategoryTypeRepository = $editionCategoryTypeRepo;
    }

    /**
     * Display a listing of the EditionCategoryType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $editionCategoryTypes = $this->editionCategoryTypeRepository->all();

        return view('edition_category_types.index')
            ->with('editionCategoryTypes', $editionCategoryTypes);
    }

    /**
     * Show the form for creating a new EditionCategoryType.
     *
     * @return Response
     */
    public function create()
    {
        return view('edition_category_types.create');
    }

    /**
     * Store a newly created EditionCategoryType in storage.
     *
     * @param CreateEditionCategoryTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateEditionCategoryTypeRequest $request)
    {
        $input = $request->all();

        $editionCategoryType = $this->editionCategoryTypeRepository->create($input);

        Flash::success('Edition Category Type saved successfully.');

        return redirect(route('editionCategoryTypes.index'));
    }

    /**
     * Display the specified EditionCategoryType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $editionCategoryType = $this->editionCategoryTypeRepository->find($id);

        if (empty($editionCategoryType)) {
            Flash::error('Edition Category Type not found');

            return redirect(route('editionCategoryTypes.index'));
        }

        return view('edition_category_types.show')->with('editionCategoryType', $editionCategoryType);
    }

    /**
     * Show the form for editing the specified EditionCategoryType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $editionCategoryType = $this->editionCategoryTypeRepository->find($id);

        if (empty($editionCategoryType)) {
            Flash::error('Edition Category Type not found');

            return redirect(route('editionCategoryTypes.index'));
        }

        return view('edition_category_types.edit')->with('editionCategoryType', $editionCategoryType);
    }

    /**
     * Update the specified EditionCategoryType in storage.
     *
     * @param int $id
     * @param UpdateEditionCategoryTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEditionCategoryTypeRequest $request)
    {
        $editionCategoryType = $this->editionCategoryTypeRepository->find($id);

        if (empty($editionCategoryType)) {
            Flash::error('Edition Category Type not found');

            return redirect(route('editionCategoryTypes.index'));
        }

        $editionCategoryType = $this->editionCategoryTypeRepository->update($request->all(), $id);

        Flash::success('Edition Category Type updated successfully.');

        return redirect(route('editionCategoryTypes.index'));
    }

    /**
     * Remove the specified EditionCategoryType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $editionCategoryType = $this->editionCategoryTypeRepository->find($id);

        if (empty($editionCategoryType)) {
            Flash::error('Edition Category Type not found');

            return redirect(route('editionCategoryTypes.index'));
        }

        $this->editionCategoryTypeRepository->delete($id);

        Flash::success('Edition Category Type deleted successfully.');

        return redirect(route('editionCategoryTypes.index'));
    }
}
