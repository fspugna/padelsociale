<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateCategoryTypeRequest;
use App\Http\Requests\UpdateCategoryTypeRequest;
use App\Repositories\CategoryTypeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CategoryTypeController extends AppBaseController
{
    /** @var  CategoryTypeRepository */
    private $categoryTypeRepository;

    public function __construct(CategoryTypeRepository $categoryTypeRepo)
    {
        $this->categoryTypeRepository = $categoryTypeRepo;
    }

    /**
     * Display a listing of the CategoryType.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $categoryTypes = $this->categoryTypeRepository->all();

        return view('admin.categoryTypes.index')
            ->with('categoryTypes', $categoryTypes);
    }

    /**
     * Show the form for creating a new CategoryType.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.categoryTypes.create');
    }

    /**
     * Store a newly created CategoryType in storage.
     *
     * @param CreateCategoryTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoryTypeRequest $request)
    {
        $input = $request->all();

        $categoryType = $this->categoryTypeRepository->create($input);

        Flash::success('Category Type saved successfully.');

        return redirect(route('admin.categoryTypes.index'));
    }

    /**
     * Display the specified CategoryType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $categoryType = $this->categoryTypeRepository->find($id);

        if (empty($categoryType)) {
            Flash::error('Category Type not found');

            return redirect(route('admin.categoryTypes.index'));
        }

        return view('admin.categoryTypes.show')->with('categoryType', $categoryType);
    }

    /**
     * Show the form for editing the specified CategoryType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $categoryType = $this->categoryTypeRepository->find($id);

        if (empty($categoryType)) {
            Flash::error('Category Type not found');

            return redirect(route('admin.categoryTypes.index'));
        }

        return view('admin.categoryTypes.edit')->with('categoryType', $categoryType);
    }

    /**
     * Update the specified CategoryType in storage.
     *
     * @param int $id
     * @param UpdateCategoryTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoryTypeRequest $request)
    {
        $categoryType = $this->categoryTypeRepository->find($id);

        if (empty($categoryType)) {
            Flash::error('Category Type not found');

            return redirect(route('admin.categoryTypes.index'));
        }

        $categoryType = $this->categoryTypeRepository->update($request->all(), $id);

        Flash::success('Category Type updated successfully.');

        return redirect(route('admin.categoryTypes.index'));
    }

    /**
     * Remove the specified CategoryType from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $categoryType = $this->categoryTypeRepository->find($id);

        if (empty($categoryType)) {
            Flash::error('Category Type not found');

            return redirect(route('admin.categoryTypes.index'));
        }

        $this->categoryTypeRepository->delete($id);

        Flash::success('Category Type deleted successfully.');

        return redirect(route('admin.categoryTypes.index'));
    }
}
