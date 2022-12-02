<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateInfoRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Repositories\InfoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\Info;

use Validator;

class InfoController extends AppBaseController
{
    /** @var  InfoRepository */
    private $infoRepository;

    public function __construct(InfoRepository $infoRepo)
    {
        $this->infoRepository = $infoRepo;
    }

    /**
     * Display a listing of the Info.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $infos = $this->infoRepository->all();

        return view('admin.infos.index')
            ->with('infos', $infos);
    }

    /**
     * Show the form for creating a new Info.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.infos.create');
    }

    /**
     * Store a newly created Info in storage.
     *
     * @param CreateInfoRequest $request
     *
     * @return Response
     */
    public function store(CreateInfoRequest $request)
    {
        $input = $request->all();

        if($request->hasFile('image')):

            $image_rule = [
                'image' => 'mimes:jpg,jpeg,png|max:10240',
            ];

            $image_messages = [
                'image.image' => "Il file caricato non è un'immagine",
                'image.mimes' => "L'immagine deve essere di tipo .jpg o .png",
                'image.max' => "ATTENZIONE: questo file presenta dei problemi. L'immagine supera i 10mb."
            ];

            $image_validator = Validator::make(['image' => $input['image']], $image_rule, $image_messages);
            if($image_validator->fails()){
                return back()->withErrors($image_validator);
            }

            $filename = Storage::disk('public')->putFile('infos', $input['image']);
            $input['image'] = $filename;
        endif;

        switch($request->submitbutton){
            case 'draft': $input['status'] = 0; break;
            case 'publish': $input['status'] = 1; break;
        }

        $attempts = 1;

        do{

            $permalink = \str_slug($input['title']);
            if($attempts > 1):
                $permalink .= '_' . $attempts;
            endif;

            $attempts++;

            $info_permalink = Info::where('permalink', '=', $permalink)->get();

        } while( count($info_permalink) > 0 );

        $input['permalink'] = $permalink;



        $info = $this->infoRepository->create($input);

        if($info->status == 1):
            /*
            $users = User::where('status', '=', 1)->get();
            foreach($users as $user):
                $user->notify(new NewsPublished($news));
            endforeach;
            */
        endif;

        Flash::success('Info saved successfully.');

        return redirect(route('admin.infos.index'));
    }

    /**
     * Display the specified Info.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $info = $this->infoRepository->find($id);

        if (empty($info)) {
            Flash::error('Info not found');

            return redirect(route('admin.infos.index'));
        }

        return view('admin.infos.show')->with('info', $info);
    }

    /**
     * Show the form for editing the specified Info.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $info = $this->infoRepository->find($id);

        if (empty($info)) {
            Flash::error('Info not found');

            return redirect(route('admin.infos.index'));
        }

        return view('admin.infos.edit')->with('info', $info);
    }

    /**
     * Update the specified Info in storage.
     *
     * @param int $id
     * @param UpdateInfoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInfoRequest $request)
    {

        $input = $request->all();

        $info = $this->infoRepository->find($id);

        if($request->hasFile('image')):

            $image_rule = [
                'image' => 'mimes:jpg,jpeg,png|max:10240',
            ];

            $image_messages = [
                'image.image' => "Il file caricato non è un'immagine",
                'image.mimes' => "L'immagine deve essere di tipo .jpg o .png",
                'image.max' => "ATTENZIONE: questo file presenta dei problemi. L'immagine supera i 10mb."
            ];

            $image_validator = Validator::make(['image' => $input['image']], $image_rule, $image_messages);
            if($image_validator->fails()){
                return back()->withErrors($image_validator);
            }

            $filename = Storage::disk('public')->putFile('infos', $input['image']);
            if($info->image != ''):
                Storage::disk('public')->delete($info->image);
            endif;
            $input['image'] = $filename;
        endif;

        switch($request->submitbutton){
            case 'draft': $input['status'] = 0; break;
            case 'publish': $input['status'] = 1; break;
            case 'delimg': $input['image'] = null; break;
        }

        $attempts = 1;

        if( empty($input['permalink']) ):
            do{

                $permalink = \str_slug($input['title']);
                if($attempts > 1):
                    $permalink .= '_' . $attempts;
                endif;

                $attempts++;

                $info_permalink = Info::where('permalink', '=', $permalink)->get();

            } while( count($info_permalink) > 0 );

            $input['permalink'] = $permalink;
        endif;

        if (empty($info)) {
            Flash::error('Info not found');

            return redirect(route('admin.infos.index'));
        }

        $info = $this->infoRepository->update($input, $id);

        if($info->status == 1 && !$info->notified):
            /*
            $users = User::where('status', '=', 1)->get();
            foreach($users as $user):
                $user->notify(new NewsPublished($news));
            endforeach;


            $news->notified = 1;
            $news->save();
            */
        endif;

        Flash::success('Info updated successfully.');

        return redirect(route('admin.infos.index'));

    }

    /**
     * Remove the specified Info from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $info = $this->infoRepository->find($id);

        if (empty($info)) {
            Flash::error('Info not found');

            return redirect(route('admin.infos.index'));
        }

        $this->infoRepository->delete($id);

        Flash::success('Info deleted successfully.');

        return redirect(route('admin.infos.index'));
    }
}
