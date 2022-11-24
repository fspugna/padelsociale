<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateUserMetaRequest;
use App\Http\Requests\UpdateUserMetaRequest;
use App\Repositories\UserMetaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class UserMetaController extends AppBaseController
{
    /** @var  UserMetaRepository */
    private $userMetaRepository;

    public function __construct(UserMetaRepository $userMetaRepo)
    {
        $this->userMetaRepository = $userMetaRepo;
    }

    /**
     * Display a listing of the UserMeta.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $userMetas = $this->userMetaRepository->all();

        return view('admin.user_metas.index')
            ->with('userMetas', $userMetas);
    }

    /**
     * Show the form for creating a new UserMeta.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.user_metas.create');
    }

    /**
     * Store a newly created UserMeta in storage.
     *
     * @param CreateUserMetaRequest $request
     *
     * @return Response
     */
    public function store(CreateUserMetaRequest $request)
    {
        $input = $request->all();

        $userMeta = $this->userMetaRepository->create($input);

        Flash::success('User Meta saved successfully.');

        return redirect(route('userMetas.index'));
    }

    /**
     * Display the specified UserMeta.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $userMeta = $this->userMetaRepository->find($id);

        if (empty($userMeta)) {
            Flash::error('User Meta not found');

            return redirect(route('userMetas.index'));
        }

        return view('admin.user_metas.show')->with('userMeta', $userMeta);
    }

    /**
     * Show the form for editing the specified UserMeta.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $userMeta = $this->userMetaRepository->find($id);

        if (empty($userMeta)) {
            Flash::error('User Meta not found');

            return redirect(route('userMetas.index'));
        }

        return view('admin.user_metas.edit')->with('userMeta', $userMeta);
    }

    /**
     * Update the specified UserMeta in storage.
     *
     * @param int $id
     * @param UpdateUserMetaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserMetaRequest $request)
    {
        $userMeta = $this->userMetaRepository->find($id);

        if (empty($userMeta)) {
            Flash::error('User Meta not found');

            return redirect(route('userMetas.index'));
        }

        $userMeta = $this->userMetaRepository->update($request->all(), $id);

        Flash::success('User Meta updated successfully.');

        return redirect(route('userMetas.index'));
    }

    /**
     * Remove the specified UserMeta from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $userMeta = $this->userMetaRepository->find($id);

        if (empty($userMeta)) {
            Flash::error('User Meta not found');

            return redirect(route('userMetas.index'));
        }

        $this->userMetaRepository->delete($id);

        Flash::success('User Meta deleted successfully.');

        return redirect(route('userMetas.index'));
    }
}
