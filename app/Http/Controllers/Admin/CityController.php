<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Repositories\CityRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\City;
use App\Models\Country;
use App\Models\Zone;
use App\Models\User;
use App\Models\Club;

class CityController extends AppBaseController
{
    /** @var  CityRepository */
    private $cityRepository;

    public function __construct(CityRepository $cityRepo)
    {
        $this->cityRepository = $cityRepo;
    }

    /**
     * Display a listing of the City.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $cities = City::orderBy('name')->paginate(50);

        return view('admin.cities.index')
            ->with('cities', $cities)
            ;
    }

    /**
     * Show the form for creating a new City.
     *
     * @return Response
     */
    public function create()
    {
        $countries = Country::all()->pluck('name', 'id');
        return view('admin.cities.create')
            ->with('countries', $countries);
    }

    /**
     * Store a newly created City in storage.
     *
     * @param CreateCityRequest $request
     *
     * @return Response
     */
    public function store(CreateCityRequest $request)
    {
        $input = $request->all();

        $input['slug'] = \str_slug($input['name']);

        $city = $this->cityRepository->create($input);

        Flash::success('City saved successfully.');

        return redirect(route('admin.cities.index'));
    }

    /**
     * Display the specified City.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $city = $this->cityRepository->find($id);

        if (empty($city)) {
            Flash::error('City not found');

            return redirect(route('admin.cities.index'));
        }

        return view('admin.cities.show')->with('city', $city);
    }

    /**
     * Show the form for editing the specified City.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $city = $this->cityRepository->find($id);
        $countries = Country::all()->pluck('name', 'id');

        if (empty($city)) {
            Flash::error('City not found');

            return redirect(route('admin.cities.index'));
        }

        return view('admin.cities.edit')
                ->with('city', $city)
                ->with('countries', $countries);
    }

    /**
     * Update the specified City in storage.
     *
     * @param int $id
     * @param UpdateCityRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCityRequest $request)
    {
        $city = $this->cityRepository->find($id);

        $input = $request->all();

        $input['slug'] = \str_slug($input['name']);

        if (empty($city)) {
            Flash::error('City not found');

            return redirect(route('admin.cities.index'));
        }

        $city = $this->cityRepository->update($input, $id);

        Flash::success('City updated successfully.');

        return redirect(route('admin.cities.index'));
    }

    /**
     * Remove the specified City from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $city = $this->cityRepository->find($id);

        if (empty($city)) {
            Flash::error('City not found');

            return redirect(route('admin.cities.index'));
        }

        $zones = Zone::where('id_city', '=', $id)->get();
        if(count($zones)>0){
            Flash::error('La città è collegata a delle zone, non la puoi cancellare');

            return redirect(route('admin.cities.index'));
        }

        $users = User::where('id_city', '=', $id)->get();
        if(count($users)>0){
            Flash::error('Esistono utenti collegati a questa città, non la puoi cancellare');

            return redirect(route('admin.cities.index'));
        }

        $clubs = Club::where('id_city', '=', $id)->get();
        if(count($clubs)>0){
            Flash::error('Esistono circoli collegati a questa città, non la puoi cancellare');

            return redirect(route('admin.cities.index'));
        }

        $this->cityRepository->delete($id);

        Flash::success('City deleted successfully.');

        return redirect(route('admin.cities.index'));
    }
}
