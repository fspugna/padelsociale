<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateEditionRequest;
use App\Http\Requests\UpdateEditionRequest;
use App\Repositories\EditionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use Carbon\Carbon;

use App\Models\Edition;
use App\Models\City;
use App\Models\Zone;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\EditionZone;
use App\Models\EditionCategory;
use App\Models\EditionCategoryType;
use App\Models\Tournament;
use App\Models\TournamentType;
use App\Models\Division;
use App\Models\Bracket;
use App\Models\Event;
use Validator;

class EditionController extends AppBaseController
{
    /** @var  EditionRepository */
    private $editionRepository;

    public function __construct(EditionRepository $editionRepo)
    {
        $this->editionRepository = $editionRepo;
    }

    /**
     * Display a listing of the Edition.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $editions = $this->editionRepository->all();

        return view('admin.editions.index')
            ->with('editions', $editions);
    }

    /**
     * Show the form for creating a new Edition.
     *
     * @return Response
     */
    public function create()
    {
        $events = Event::orderBy('name')->pluck('name', 'id');
        $zones = Zone::with('city')->get();
        $categories = Category::all();
        $categoryTypes = CategoryType::all();
        $tournament_types = TournamentType::all()->pluck('tournament_type', 'id');

        $tournaments = [];

        $edition = new Edition;

        return view('admin.editions.create')
                    ->with('events', $events)
                    ->with('zones', $zones)
                    ->with('categories', $categories)
                    ->with('categoryTypes', $categoryTypes)
                    ->with('tournament_types', $tournament_types)
                    ->with('tournaments', $tournaments)
                    ->with('edition', $edition)
                    ->with('tournaments_ref', [])
                    ;
    }

    /**
     * Store a newly created Edition in storage.
     *
     * @param CreateEditionRequest $request
     *
     * @return Response
     */
    public function store(CreateEditionRequest $request)
    {
        $input = $request->all();

        $filename = '';
        if($request->hasFile('logo')):

            $image_rule = [
                'logo' => 'mimes:jpg,jpeg,png|max:10240',
            ];

            $image_messages = [
                'logo.image' => "Il file caricato non è un'immagine",
                'logo.mimes' => "L'immagine deve essere di tipo .jpg o .png",
                'logo.max' => "ATTENZIONE: questo file presenta dei problemi. L'immagine supera i 10mb."
            ];

            $image_validator = Validator::make(['logo' => $input['logo']], $image_rule, $image_messages);
            if($image_validator->fails()){
                return back()->withErrors($image_validator);
            }

            $filename = Storage::disk('public')->putFile('logos', $input['logo']);
            $input['logo'] = $filename;
        endif;

        if (!isset($input['zones'])) {
            //return redirect(route('admin.editions.create'));
            return back()->withInput()->withErrors(['Selezionare almeno una zona']);
        }

        unset($input['daterange']);

        //dd($request->hasFile('logo'), $input, $filename);
        $edition = $this->editionRepository->create($input);

        $order = 0;

        if(isset($input['zones'])){
            foreach($input['zones'] as $zone):
                $editionZone = new EditionZone();
                $editionZone->id_edition = $edition->id;
                $editionZone->id_zone = $zone;
                $editionZone->order = ++$order;
                $editionZone->save();
            endforeach;
        }else{
            return back()->withInput()->withErrors(['Selezionare almeno una zona']);
        }


        if(isset($input['categories'])){
            foreach($input['categories'] as $category):
                $editionCategory = new EditionCategory();
                $editionCategory->id_edition = $edition->id;
                $editionCategory->id_category = $category;
                $editionCategory->save();
            endforeach;
        }else{
            return back()->withInput()->withErrors(['Selezionare almeno una categoria']);
        }

        if(isset($input['category_types'])){
            foreach($input['category_types'] as $categoryType):
                $editionCategoryType = new EditionCategoryType();
                $editionCategoryType->id_edition = $edition->id;
                $editionCategoryType->id_category_type = $categoryType;
                $editionCategoryType->save();
            endforeach;
        }else{
            return back()->withInput()->withErrors(['Selezionare almeno una tipologia']);
        }


        Flash::success('Edition saved successfully.');

        return redirect(route('admin.editions.edit', ['edition' => $edition->id]));
    }

    /**
     * Display the specified Edition.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $edition = $this->editionRepository->find($id);

        if (empty($edition)) {
            Flash::error('Edition not found');

            return redirect(route('admin.editions.index'));
        }

        return view('admin.editions.show')->with('edition', $edition);
    }

    /**
     * Show the form for editing the specified Edition.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $edition = $this->editionRepository->find($id);

        $events = Event::orderBy('name')->pluck('name', 'id');
        $zones = Zone::with('city')->get();
        $tournament_types = TournamentType::all()->pluck('tournament_type', 'id');

        foreach($tournament_types as $k => $tt):
            $tournament_types[$k] = trans('labels.'.$tt);
        endforeach;

        $editionZones = EditionZone::where('id_edition', '=', $id)->get();
        foreach($zones as $k => $zone){
            foreach($editionZones as $ez){
                if( $zone->id == $ez->id_zone){
                    unset($zones[$k]);
                }
            }
        }


        $categories = Category::all();
        $editionCategories = EditionCategory::where('id_edition', '=', $id)->get();
        foreach($categories as $k => $category){
            foreach($editionCategories as $ec){
                if( $category->id == $ec->id_category){
                    unset($categories[$k]);
                }
            }
        }

        $categoryTypes = CategoryType::all();
        $editionCategoryTypes = EditionCategoryType::where('id_edition', '=', $id)->get();
        foreach($categoryTypes as $k => $category){
            foreach($editionCategoryTypes as $ec){
                if( $category->id == $ec->id_category_type){
                    unset($categoryTypes[$k]);
                }
            }
        }


        $tournaments = Tournament::where('id_edition', '=', $id)->orderBy('date_start', 'desc')->get();
        foreach($tournaments as $tournament){
            $tournament->tournamentType->tournament_type = trans('labels.'.$tournament->tournamentType->tournament_type);
        }

        $tournaments_ref = Tournament::where('id_edition', '=', $id)
                                     ->where('id_tournament_type', '=', TournamentType::where('tournament_type', '=', 'step_1')->first()->id )
                                     ->orderBy('date_start', 'desc')
                                     ->get()
                                     ->pluck('name', 'id')
                                     ->toArray();

        $tournaments_ref[0] = 'NESSUNO';

        if (empty($edition)) {
            Flash::error('Edition not found');

            return redirect(route('admin.editions.index'));
        }

        return view('admin.editions.edit')
                ->with('edition', $edition)
                ->with('events', $events)
                ->with('zones', $zones)
                ->with('tournament_types', $tournament_types)
                ->with('editionZones', $editionZones)
                ->with('categories', $categories)
                ->with('editionCategories', $editionCategories)
                ->with('categoryTypes', $categoryTypes)
                ->with('editionCategoryTypes', $editionCategoryTypes)
                ->with('tournaments', $tournaments)
                ->with('tournaments_ref', $tournaments_ref)
                ;
    }

    /**
     * Update the specified Edition in storage.
     *
     * @param int $id
     * @param UpdateEditionRequest $request
     *
     * @return Response
     */
    public function update(Request $request, $id_edition)
    {
        $input = $request->all();

        $edition = Edition::find($id_edition);
        $edition->id_event = $input['id_event'];
        $edition->edition_name = $input['edition_name'];
        $edition->edition_type = $input['edition_type'];

        if(isset($input['edition_description'])):
            $edition->edition_description = $input['edition_description'];
        endif;

        if(isset($input['edition_rules'])):
            $edition->edition_rules = $input['edition_rules'];
        endif;

        if(isset($input['edition_zone_rules'])):
            $edition->edition_zone_rules = $input['edition_zone_rules'];
        endif;

        if(isset($input['edition_awards'])):
            $edition->edition_awards = $input['edition_awards'];
        endif;

        if(isset($input['edition_zones_and_clubs'])):
            $edition->edition_zones_and_clubs = $input['edition_zones_and_clubs'];
        endif;

        $edition->subscription_fee = $input['subscription_fee'];


        if(isset($input['btn_del_logo'])):
            $input['logo'] = '';
        else:
            if($request->hasFile('logo')):

                $image_rule = [
                    'logo' => 'mimes:jpg,jpeg,png|max:10240',
                ];

                $image_messages = [
                    'logo.image' => "Il file caricato non è un'immagine",
                    'logo.mimes' => "L'immagine deve essere di tipo .jpg o .png",
                    'logo.max' => "ATTENZIONE: questo file presenta dei problemi. L'immagine supera i 10mb."
                ];

                $image_validator = Validator::make(['logo' => $input['logo']], $image_rule, $image_messages);
                if($image_validator->fails()){
                    return back()->withErrors($image_validator);
                }

                $filename = Storage::disk('public')->putFile('logos', $input['logo']);
                if($edition->logo != ''):
                    Storage::disk('public')->delete($edition->logo);
                endif;
                $input['logo'] = $filename;
            endif;
        endif;

        if( isset($input['logo']) ){
            $edition->logo = $input['logo'];
        }

        if (!isset($input['zones'])) {
            //return redirect(route('admin.editions.create'));
            return back()->withInput()->withErrors(['Selezionare almeno una zona']);
        }

        if (empty($edition)) {
            Flash::error('Edition not found');

            return redirect(route('admin.editions.index'));
        }


        if(isset($input['zones'])){

            EditionZone::where('id_edition', '=', $edition->id)->delete();

            $order = 0;

            foreach($input['zones'] as $zone):
                $editionZone = new EditionZone();
                $editionZone->id_edition = $edition->id;
                $editionZone->id_zone = $zone;
                $editionZone->order = ++$order;
                $editionZone->save();
            endforeach;
        }else{
            return back()->withInput()->withErrors(['Selezionare almeno una zona']);
        }

        if(isset($input['categories'])){

            EditionCategory::where('id_edition', '=', $edition->id)->delete();

            foreach($input['categories'] as $category):
                $editionCategory = new EditionCategory();
                $editionCategory->id_edition = $edition->id;
                $editionCategory->id_category = $category;
                $editionCategory->save();
            endforeach;
        }else{
            return back()->withInput()->withErrors(['Selezionare almeno una categoria']);
        }


        if(isset($input['category_types'])){

            EditionCategoryType::where('id_edition', '=', $edition->id)->delete();

            foreach($input['category_types'] as $categoryType):
                $editionCategoryType = new EditionCategoryType();
                $editionCategoryType->id_edition = $edition->id;
                $editionCategoryType->id_category_type = $categoryType;
                $editionCategoryType->save();
            endforeach;
        }else{
            return back()->withInput()->withErrors(['Selezionare almeno una tipologia']);
        }

        $edition->save();

        if( isset($input['btn_save_edition']) && $input['btn_save_edition'] == 'Salva tutto e ricrea categorie'){
            /** Cancello Divisioni / Tabelloni se nnon esiste più la zona o la categoria o la tipologia di riferimento
             *  e se non sono stati ancora generati
             */
            $tournaments = Tournament::where('id_edition', '=', $edition->id)
                                    ->where('generated', '=', 1)
                                    ->get();

            if( $tournaments ){

                foreach($tournaments as $tournament){

                    if( $tournament->id_tournament_type == 1 ){
                        $divisions = Division::where('id_tournament', '=', $tournament->id)
                                             ->whereNotIn('id_zone', array_map('intval', $input['zones']))->get();

                        foreach($divisions as $division){
                            if($division->generated){
                                return back()->withInput()->withErrors(['Non puoi cancellare la zona ' . $division->zone->name . ' perché esistono dei gironi associati']);
                            }else{
                                $division->delete();
                            }
                        }
                        $divisions = Division::where('id_tournament', '=', $tournament->id)
                                             ->whereNotIn('id_category', array_map('intval', $input['categories']))->get();
                        foreach($divisions as $division){
                            if($division->generated){
                                return back()->withInput()->withErrors(['Non puoi cancellare la categoria ' . $division->category->name . ' perché esistono dei gironi associati']);
                            }else{
                                $division->delete();
                            }
                        }
                        $divisions = Division::where('id_tournament', '=', $tournament->id)
                                             ->whereNotIn('id_category_type', array_map('intval', $input['category_types']))->get();
                        foreach($divisions as $division){
                            if($division->generated){
                                return back()->withInput()->withErrors(['Non puoi cancellare la tipologia ' . $division->categoryType->name . ' perché esistono dei gironi associati']);
                            }else{
                                $division->delete();
                            }
                        }
                    }elseif( $tournament->id_tournament_type == 2 ){
                        $brackets = Bracket::where('id_tournament', '=', $tournament->id)
                                           ->whereNotIn('id_zone', array_map('intval', $input['zones']))->get();
                        foreach($brackets as $bracket){
                            if( $bracket->generated ){
                                return back()->withInput()->withErrors(['Non puoi cancellare la zona ' . $division->zone->name . ' perché esiste un tabellone associato']);
                            }else{
                                $division->delete();
                            }
                        }
                        $brackets = Bracket::where('id_tournament', '=', $tournament->id)
                                           ->whereNotIn('id_category', array_map('intval', $input['categories']))->get();
                        foreach($brackets as $bracket){
                            if( $bracket->generated ){
                                return back()->withInput()->withErrors(['Non puoi cancellare la categoria ' . $division->category->name . ' perché esiste un tabellone associato']);
                            }else{
                                $division->delete();
                            }
                        }
                        $brackets = Bracket::where('id_tournament', '=', $tournament->id)
                                            ->whereNotIn('id_category_type', array_map('intval', $input['category_types']))->get();
                        foreach($brackets as $bracket){
                            if( $bracket->generated ){
                                return back()->withInput()->withErrors(['Non puoi cancellare la tipologia ' . $division->categoryType->name . ' perché esiste un tabellone associato']);
                            }else{
                                $division->delete();
                            }
                        }
                    }
                }
            }

            //dd($edition);

            /*
            if(isset($input['zones'])){

                EditionZone::where('id_edition', '=', $edition->id)->delete();

                $order = 0;

                foreach($input['zones'] as $zone):
                    $editionZone = new EditionZone();
                    $editionZone->id_edition = $edition->id;
                    $editionZone->id_zone = $zone;
                    $editionZone->order = ++$order;
                    $editionZone->save();
                endforeach;
            }else{
                return back()->withInput()->withErrors(['Selezionare almeno una zona']);
            }

            if(isset($input['categories'])){

                EditionCategory::where('id_edition', '=', $edition->id)->delete();

                foreach($input['categories'] as $category):
                    $editionCategory = new EditionCategory();
                    $editionCategory->id_edition = $edition->id;
                    $editionCategory->id_category = $category;
                    $editionCategory->save();
                endforeach;
            }else{
                return back()->withInput()->withErrors(['Selezionare almeno una categoria']);
            }


            if(isset($input['category_types'])){

                EditionCategoryType::where('id_edition', '=', $edition->id)->delete();

                foreach($input['category_types'] as $categoryType):
                    $editionCategoryType = new EditionCategoryType();
                    $editionCategoryType->id_edition = $edition->id;
                    $editionCategoryType->id_category_type = $categoryType;
                    $editionCategoryType->save();
                endforeach;
            }else{
                return back()->withInput()->withErrors(['Selezionare almeno una tipologia']);
            }
            */


            if( $tournaments ){
                foreach($tournaments as $tournament){
                    foreach($edition->zones as $zone){
                        foreach($edition->categories as $category){
                            foreach($edition->categoryTypes as $category_type){

                                if( $tournament->id_tournament_type == 1 ){

                                    $division = Division::where('id_tournament', $tournament->id)
                                                        ->where('id_zone', $zone['id_zone'])
                                                        ->where('id_category', $category['id_category'])
                                                        ->where('id_category_type', $category_type['id_category_type'])
                                                        ->first();

                                    if( !$division ){

                                        /** Creo le divisioni */
                                        $division = new Division();
                                        $division->id_tournament = $tournament->id;
                                        $division->id_zone = $zone['id_zone'];
                                        $division->id_category = $category['id_category'];
                                        $division->id_category_type = $category_type['id_category_type'];
                                        $division->save();

                                    }

                                }elseif( $tournament->id_tournament_type == 2 ){

                                    $bracket = Bracket::where('id_tournament', $tournament->id)
                                                        ->where('id_zone', $zone['id_zone'])
                                                        ->where('id_category', $category['id_category'])
                                                        ->where('id_category_type', $category_type['id_category_type'])
                                                        ->first();

                                    if( !$bracket ){

                                        /** Creo le divisioni */
                                        $bracket = new Bracket();
                                        $bracket->id_tournament = $tournament->id;
                                        $bracket->id_zone = $zone['id_zone'];
                                        $bracket->id_category = $category['id_category'];
                                        $bracket->id_category_type = $category_type['id_category_type'];
                                        $bracket->save();

                                    }

                                }
                            }
                        }
                    }
                }
            }

        }

        Flash::success('Edition updated successfully.');

        return redirect(route('admin.editions.edit', ['id' => $edition->id]));
    }

    /**
     * Remove the specified Edition from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $edition = $this->editionRepository->find($id);

        if (empty($edition)) {
            Flash::error('Edition not found');

            return redirect(route('admin.editions.index'));
        }

        $this->editionRepository->delete($id);

        Flash::success('Edition deleted successfully.');

        return redirect(route('admin.editions.index'));
    }

}
