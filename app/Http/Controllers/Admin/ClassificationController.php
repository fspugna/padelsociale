<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateClassificationRequest;
use App\Http\Requests\UpdateClassificationRequest;
use App\Repositories\ClassificationRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ClassificationController extends AppBaseController
{
    /** @var  ClassificationRepository */
    private $classificationRepository;

    public function __construct(ClassificationRepository $classificationRepo)
    {
        $this->classificationRepository = $classificationRepo;
    }

    /**
     * Display a listing of the Classification.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $classifications = $this->classificationRepository->all();

        return view('admin.classifications.index')
            ->with('classifications', $classifications);
    }

    /**
     * Show the form for creating a new Classification.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.classifications.create');
    }

    /**
     * Store a newly created Classification in storage.
     *
     * @param CreateClassificationRequest $request
     *
     * @return Response
     */
    public function store(CreateClassificationRequest $request)
    {
        $input = $request->all();

        $classification = $this->classificationRepository->create($input);

        Flash::success('Classification saved successfully.');

        return redirect(route('classifications.index'));
    }

    /**
     * Display the specified Classification.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $classification = $this->classificationRepository->find($id);

        if (empty($classification)) {
            Flash::error('Classification not found');

            return redirect(route('classifications.index'));
        }

        return view('admin.classifications.show')->with('classification', $classification);
    }

    /**
     * Show the form for editing the specified Classification.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $classification = $this->classificationRepository->find($id);

        if (empty($classification)) {
            Flash::error('Classification not found');

            return redirect(route('classifications.index'));
        }

        return view('admin.classifications.edit')->with('classification', $classification);
    }

    /**
     * Update the specified Classification in storage.
     *
     * @param int $id
     * @param UpdateClassificationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClassificationRequest $request)
    {
        $classification = $this->classificationRepository->find($id);

        if (empty($classification)) {
            Flash::error('Classification not found');

            return redirect(route('classifications.index'));
        }

        $classification = $this->classificationRepository->update($request->all(), $id);

        Flash::success('Classification updated successfully.');

        return redirect(route('classifications.index'));
    }

    /**
     * Remove the specified Classification from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $classification = $this->classificationRepository->find($id);

        if (empty($classification)) {
            Flash::error('Classification not found');

            return redirect(route('classifications.index'));
        }

        $this->classificationRepository->delete($id);

        Flash::success('Classification deleted successfully.');

        return redirect(route('classifications.index'));
    }
}
