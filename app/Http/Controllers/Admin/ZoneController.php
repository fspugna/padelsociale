<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateZoneRequest;
use App\Http\Requests\UpdateZoneRequest;
use App\Repositories\ZoneRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\City;
use App\Models\Zone;
use App\Models\ZoneClub;

class ZoneController extends AppBaseController
{
    /** @var  ZoneRepository */
    private $zoneRepository;

    public function __construct(ZoneRepository $zoneRepo)
    {
        $this->zoneRepository = $zoneRepo;
    }

    /**
     * Display a listing of the Zone.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {

        $input = $request->all();

        $zones = Zone::select('zones.id', 'zones.name', 'cities.name as city')
                        ->join('cities', 'zones.id_city', '=', 'cities.id')
                        ->with('city')
                        ->with('clubs')
                        ->orderBy('cities.name')
                        ->orderBy('zones.name');

        $filter_city = '';
        if( isset($input['filter-city']) && !empty($input['filter-city']) ){
            $filter_city = $input['filter-city'];
            $zones = $zones->where('id_city', $filter_city);
        }

        $zones = $zones->paginate(50);

        $cities = City::orderBy('name')->pluck('name', 'id')->toArray();
        $cities = ['' => 'TUTTE'] + $cities;

        return view('admin.zones.index')->with('zones', $zones)
                                        ->with('cities', $cities)
                                        ->with('filter_city', $filter_city)
                                        ;
    }

    /**
     * Show the form for creating a new Zone.
     *
     * @return Response
     */
    public function create()
    {
        $cities = City::all()->pluck('name', 'id');
        return view('admin.zones.create')
            ->with('cities', $cities)
            ->with('clubs', []);
    }

    /**
     * Store a newly created Zone in storage.
     *
     * @param CreateZoneRequest $request
     *
     * @return Response
     */
    public function store(CreateZoneRequest $request)
    {
        $input = $request->all();

        $zone = $this->zoneRepository->create($input);

        $input = $request->all();

        if( isset($input['id_club']) ){

            ZoneClub::where('id_zone', '=', $zone->id)->delete();

            foreach($input['id_club'] as $id_club){

                $zoneClub = new ZoneClub;
                $zoneClub->id_zone = $zone->id;
                $zoneClub->id_club = $id_club;
                $zoneClub->save();

            }
        }


        Flash::success('Zone saved successfully.');

        return redirect(route('admin.zones.index'));
    }

    /**
     * Display the specified Zone.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $zone = $this->zoneRepository->find($id);

        if (empty($zone)) {
            Flash::error('Zone not found');

            return redirect(route('admin.zones.index'));
        }

        return view('admin.zones.show')->with('zone', $zone);
    }

    /**
     * Show the form for editing the specified Zone.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $zone = $this->zoneRepository->find($id);
        $cities = City::all()->pluck('name', 'id');

        $clubs = ZoneClub::where('id_zone', '=', $id)->get();

        if (empty($zone)) {
            Flash::error('Zone not found');

            return redirect(route('admin.zones.index'));
        }

        return view('admin.zones.edit')
                ->with('zone', $zone)
                ->with('cities', $cities)
                ->with('clubs', $clubs)
                ;
    }

    /**
     * Update the specified Zone in storage.
     *
     * @param int $id
     * @param UpdateZoneRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateZoneRequest $request)
    {

        $zone = $this->zoneRepository->find($id);

        if (empty($zone)) {
            Flash::error('Zone not found');

            return redirect(route('admin.zones.index'));
        }

        $zone = $this->zoneRepository->update($request->all(), $id);

        $input = $request->all();

        if( isset($input['id_club']) ){

            ZoneClub::where('id_zone', '=', $zone->id)->delete();

            foreach($input['id_club'] as $id_club){

                $zoneClub = new ZoneClub;
                $zoneClub->id_zone = $zone->id;
                $zoneClub->id_club = $id_club;
                $zoneClub->save();

            }
        }

        Flash::success('Zone updated successfully.');

        return redirect(route('admin.zones.index'));
    }

    /**
     * Remove the specified Zone from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $zone = $this->zoneRepository->find($id);

        if (empty($zone)) {
            Flash::error('Zone not found');

            return redirect(route('admin.zones.index'));
        }

        $this->zoneRepository->delete($id);

        Flash::success('Zone deleted successfully.');

        return redirect(route('admin.zones.index'));
    }

}
