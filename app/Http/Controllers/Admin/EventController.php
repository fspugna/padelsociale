<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Edition;

use App\Http\Controllers\Controller;
use App\Repositories\EventRepository;
use App\Http\Requests\CreateEventRequest;
use App\Repositories\EditionRepository;
use Flash;

class EventController extends Controller
{
     /** @var  EventRepository */
     private $eventRepository, $editionRepository;

     public function __construct(EventRepository $eventRepo, EditionRepository $editionRepo)
     {
         $this->eventRepository = $eventRepo;
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
        $events = $this->eventRepository->all();

        return view('admin.events.index')
            ->with('events', $events);
    }

    public function create()
    {
        $event = new Event;

        return view('admin.events.create')
                    ->with('event', $event)
                    ;
    }

    public function store(CreateEventRequest $request)
    {
        $input = $request->all();

        //dd($request->hasFile('logo'), $input, $filename);
        $event = $this->eventRepository->create($input);

        Flash::success('Evento creato con successo');

        return redirect(route('admin.events.index'));
    }

    public function edit($id)
    {
        $event = $this->eventRepository->find($id);

        return view('admin.events.edit')->with('event', $event);
    }

    public function update(Request $request, $id)
    {

        $input = $request->all();
        $event = $this->eventRepository->find($id);
        $event->name = $input['name'];
        $event->save();
        return redirect(route('admin.events.index'));

    }

    public function destroy($id)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Flash::error('Evento non trovato');

            return redirect(route('admin.events.index'));
        }

        $editions = Edition::where('id_event', $id)->get()->count();

        if( $editions === 0 ){
            $this->eventRepository->delete($id);
            Flash::success('Evento eliminato con successo.');
        }else{
            return back()->withErrors('Non puoi eliminare questo evento perché esistono delle edizioni associate');
        }



        return redirect(route('admin.events.index'));
    }

}
