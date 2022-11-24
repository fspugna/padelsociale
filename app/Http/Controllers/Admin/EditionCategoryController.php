<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateEditionCategoryRequest;
use App\Http\Requests\UpdateEditionCategoryRequest;
use App\Repositories\EditionCategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class EditionCategoryController extends AppBaseController
{
    /** @var  EditionCategoryRepository */
    private $editionCategoryRepository;

    public function __construct(EditionCategoryRepository $editionCategoryRepo)
    {
        $this->editionCategoryRepository = $editionCategoryRepo;
    }

    /**
     * Display a listing of the EditionCategory.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $editionCategories = $this->editionCategoryRepository->all();

        return view('edition_categories.index')
            ->with('editionCategories', $editionCategories);
    }

    /**
     * Show the form for creating a new EditionCategory.
     *
     * @return Response
     */
    public function create()
    {
        return view('edition_categories.create');
    }

    /**
     * Store a newly created EditionCategory in storage.
     *
     * @param CreateEditionCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateEditionCategoryRequest $request)
    {
        $input = $request->all();

        $editionCategory = $this->editionCategoryRepository->create($input);

        Flash::success('Edition Category saved successfully.');

        return redirect(route('editionCategories.index'));
    }

    /**
     * Display the specified EditionCategory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $editionCategory = $this->editionCategoryRepository->find($id);

        if (empty($editionCategory)) {
            Flash::error('Edition Category not found');

            return redirect(route('editionCategories.index'));
        }

        return view('edition_categories.show')->with('editionCategory', $editionCategory);
    }

    /**
     * Show the form for editing the specified EditionCategory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $editionCategory = $this->editionCategoryRepository->find($id);

        if (empty($editionCategory)) {
            Flash::error('Edition Category not found');

            return redirect(route('editionCategories.index'));
        }

        return view('edition_categories.edit')->with('editionCategory', $editionCategory);
    }

    /**
     * Update the specified EditionCategory in storage.
     *
     * @param int $id
     * @param UpdateEditionCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEditionCategoryRequest $request)
    {
        $editionCategory = $this->editionCategoryRepository->find($id);

        if (empty($editionCategory)) {
            Flash::error('Edition Category not found');

            return redirect(route('editionCategories.index'));
        }

        $editionCategory = $this->editionCategoryRepository->update($request->all(), $id);

        Flash::success('Edition Category updated successfully.');

        return redirect(route('editionCategories.index'));
    }

    /**
     * Remove the specified EditionCategory from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $editionCategory = $this->editionCategoryRepository->find($id);

        if (empty($editionCategory)) {
            Flash::error('Edition Category not found');

            return redirect(route('editionCategories.index'));
        }

        $this->editionCategoryRepository->delete($id);

        Flash::success('Edition Category deleted successfully.');

        return redirect(route('editionCategories.index'));
    }
}
