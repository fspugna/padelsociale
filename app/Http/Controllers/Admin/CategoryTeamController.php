<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateCategoryTeamRequest;
use App\Http\Requests\UpdateCategoryTeamRequest;
use App\Repositories\CategoryTeamRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CategoryTeamController extends AppBaseController
{
    /** @var  CategoryTeamRepository */
    private $categoryTeamRepository;

    public function __construct(CategoryTeamRepository $categoryTeamRepo)
    {
        $this->categoryTeamRepository = $categoryTeamRepo;
    }

    /**
     * Display a listing of the CategoryTeam.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $categoryTeams = $this->categoryTeamRepository->all();

        return view('admin.category_teams.index')
            ->with('categoryTeams', $categoryTeams);
    }

    /**
     * Show the form for creating a new CategoryTeam.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.category_teams.create');
    }

    /**
     * Store a newly created CategoryTeam in storage.
     *
     * @param CreateCategoryTeamRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoryTeamRequest $request)
    {
        $input = $request->all();

        $categoryTeam = $this->categoryTeamRepository->create($input);

        Flash::success('Category Team saved successfully.');

        return redirect(route('categoryTeams.index'));
    }

    /**
     * Display the specified CategoryTeam.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $categoryTeam = $this->categoryTeamRepository->find($id);

        if (empty($categoryTeam)) {
            Flash::error('Category Team not found');

            return redirect(route('categoryTeams.index'));
        }

        return view('admin.category_teams.show')->with('categoryTeam', $categoryTeam);
    }

    /**
     * Show the form for editing the specified CategoryTeam.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $categoryTeam = $this->categoryTeamRepository->find($id);

        if (empty($categoryTeam)) {
            Flash::error('Category Team not found');

            return redirect(route('categoryTeams.index'));
        }

        return view('admin.category_teams.edit')->with('categoryTeam', $categoryTeam);
    }

    /**
     * Update the specified CategoryTeam in storage.
     *
     * @param int $id
     * @param UpdateCategoryTeamRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoryTeamRequest $request)
    {
        $categoryTeam = $this->categoryTeamRepository->find($id);

        if (empty($categoryTeam)) {
            Flash::error('Category Team not found');

            return redirect(route('categoryTeams.index'));
        }

        $categoryTeam = $this->categoryTeamRepository->update($request->all(), $id);

        Flash::success('Category Team updated successfully.');

        return redirect(route('categoryTeams.index'));
    }

    /**
     * Remove the specified CategoryTeam from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $categoryTeam = $this->categoryTeamRepository->find($id);

        if (empty($categoryTeam)) {
            Flash::error('Category Team not found');

            return redirect(route('categoryTeams.index'));
        }

        $this->categoryTeamRepository->delete($id);

        Flash::success('Category Team deleted successfully.');

        return redirect(route('categoryTeams.index'));
    }
}
