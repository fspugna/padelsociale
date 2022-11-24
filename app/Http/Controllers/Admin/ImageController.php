<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Repositories\ImageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\Image;

class ImageController extends AppBaseController
{
    /** @var  ImageRepository */
    private $imageRepository;

    public function __construct(ImageRepository $imageRepo)
    {
        $this->imageRepository = $imageRepo;
    }

    /**
     * Display a listing of the Image.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $images = $this->imageRepository->all();

        return view('admin.images.index')
            ->with('images', $images);
    }

    /**
     * Show the form for creating a new Image.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.images.create');
    }

    /**
     * Store a newly created Image in storage.
     *
     * @param CreateImageRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        
        foreach( $request->allFiles() as $k => $img ){

            //echo $img->getClientOriginalName();                         
            $path = $img->store('images');          
            $image = new Image;
            $image->path = $path;
            $image->label = $img->getClientOriginalName(); 
            $image->id_user = $input['id_user'];
            $image->id_match = $input['id_match'];
            $image->save();

        }
        
        return response('ok', 200);

    }

    /**
     * Display the specified Image.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $image = $this->imageRepository->find($id);

        if (empty($image)) {
            Flash::error('Image not found');

            return redirect(route('admin.images.index'));
        }

        return view('admin.images.show')->with('image', $image);
    }

    /**
     * Show the form for editing the specified Image.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $image = $this->imageRepository->find($id);

        if (empty($image)) {
            Flash::error('Image not found');

            return redirect(route('admin.images.index'));
        }

        return view('admin.images.edit')->with('image', $image);
    }

    /**
     * Update the specified Image in storage.
     *
     * @param int $id
     * @param UpdateImageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImageRequest $request)
    {
        $image = $this->imageRepository->find($id);

        if (empty($image)) {
            Flash::error('Image not found');

            return redirect(route('admin.images.index'));
        }

        $image = $this->imageRepository->update($request->all(), $id);

        Flash::success('Image updated successfully.');

        return redirect(route('admin.images.index'));
    }

    /**
     * Remove the specified Image from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        $input = $request->all();

        foreach($input['del_image'] as $id_image):
            Image::where('id', '=', $id_image)->delete();
        endforeach;

        return redirect(route('admin.images.match.index', ['id_match' => $input['id_match']]));
    }


    public function imgMatchList($id_match){
        
        $images = Image::where('id_match', '=', $id_match)->get();

        return view('admin.images.index')
             ->with('images', $images)
             ->with('id_match', $id_match)
             ->with('flag_delete', false)
             ;

    }

    public function imgMatchDelete($id_match){

        $images = Image::where('id_match', '=', $id_match)->get();

        return view('admin.images.index')
                ->with('images', $images)
                ->with('id_match', $id_match)
                ->with('flag_delete', true)
                ;
    }

    public function imgMatchCreate($id_match){
        return view('admin.images.create')
                ->with('id_match', $id_match)
                ->with('id_user', Auth::id())                
                ->with('flag_delete', false)
                ;
    }

    
}
