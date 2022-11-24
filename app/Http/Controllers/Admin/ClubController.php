<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateClubRequest;
use App\Http\Requests\UpdateClubRequest;
use App\Repositories\ClubRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\Club;
use App\Models\Zone;

class ClubController extends AppBaseController
{
    /** @var  ClubRepository */
    private $clubRepository;

    public function __construct(ClubRepository $clubRepo)
    {
        $this->clubRepository = $clubRepo;
    }

    /**
     * Display a listing of the Club.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $clubs = $this->clubRepository->all();

        return view('admin.clubs.index')
            ->with('clubs', $clubs);
    }

    /**
     * Show the form for creating a new Club.
     *
     * @return Response
     */
    public function create()
    {
        $zonesRes = Zone::all();

        $zones = array();
        foreach($zonesRes as $zone){
            $zones[$zone->id] = $zone->city->name . ' - ' . $zone->name;
        }
        return view('admin.clubs.create')
                    ->with('zones', $zones);
    }

    /**
     * Store a newly created Club in storage.
     *
     * @param CreateClubRequest $request
     *
     * @return Response
     */
    public function store(CreateClubRequest $request)
    {
        $input = $request->all();

        $club = $this->clubRepository->create($input);

        Flash::success('Club saved successfully.');

        return redirect(route('admin.clubs.index'));
    }

    /**
     * Display the specified Club.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $club = $this->clubRepository->find($id);

        if (empty($club)) {
            Flash::error('Club not found');

            return redirect(route('admin.clubs.index'));
        }

        return view('admin.clubs.show')->with('club', $club);
    }

    /**
     * Show the form for editing the specified Club.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $club = $this->clubRepository->find($id);

        if (empty($club)) {
            Flash::error('Club not found');

            return redirect(route('admin.clubs.index'));
        }

        return view('admin.clubs.edit')->with('club', $club);
    }

    /**
     * Update the specified Club in storage.
     *
     * @param int $id
     * @param UpdateClubRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClubRequest $request)
    {
        $club = $this->clubRepository->find($id);

        if (empty($club)) {
            Flash::error('Club not found');

            return redirect(route('admin.clubs.index'));
        }

        $club = $this->clubRepository->update($request->all(), $id);

        Flash::success('Club updated successfully.');

        return redirect(route('admin.clubs.index'));
    }

    /**
     * Remove the specified Club from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $club = $this->clubRepository->find($id);

        if (empty($club)) {
            Flash::error('Club not found');

            return redirect(route('admin.clubs.index'));
        }

        $this->clubRepository->delete($id);

        Flash::success('Club deleted successfully.');

        return redirect(route('admin.clubs.index'));
    }


    public function clubsByZone($id_zone){        
        $clubs = Club::where('id_zone', '=', $id_zone)->get();        
        return response()->json($clubs);
    }

    public function search(Request $request){
        
        if($request->has('q')){
            
            $search = $request->input('q');

            $clubs = Club::where('name', 'like', '%'.$search.'%')                            
                        ->orWhere('address', 'like', '%'.$search.'%')                                                                                    
                        ->get();                            
        
            return $clubs;
        }else{
            return response('ERROR: q param missing', 500);
        }
    
    }

    public function get($id_club){
        return response()->json( Club::where('id', '=', $id_club)->first());        
    }
}