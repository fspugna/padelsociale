<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreatePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Repositories\PageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Validator;

use App\Models\Page;

class PageController extends AppBaseController
{
    /** @var  PageRepository */
    private $pageRepository;

    public function __construct(PageRepository $pageRepo)
    {
        $this->pageRepository = $pageRepo;
    }

    /**
     * Display a listing of the Page.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $pages = Page::orderBy('title', 'asc')->paginate(20)->appends(request()->except('page'));;

        return view('admin.pages.index')
            ->with('pages', $pages);
    }

    /**
     * Show the form for creating a new Page.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created Page in storage.
     *
     * @param CreatePageRequest $request
     *
     * @return Response
     */
    public function store(CreatePageRequest $request)
    {

        $input = $request->all();

        if($request->hasFile('image')):

            $image_rule = [
                'image' => 'image|mimes:jpg,jpeg,png|max:10240',
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

            $filename = Storage::disk('public')->putFile('pages', $input['image']);
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

            $page_permalink = Page::where('permalink', '=', $permalink)->get();

        } while( count($page_permalink) > 0 );

        $input['permalink'] = $permalink;



        $page = $this->pageRepository->create($input);

        if($page->status == 1):
            /*
            $users = User::where('status', '=', 1)->get();
            foreach($users as $user):
                $user->notify(new NewsPublished($news));
            endforeach;
            */
        endif;

        Flash::success('Page saved successfully.');

        return redirect(route('admin.pages.index'));
    }

    /**
     * Display the specified Page.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $page = $this->pageRepository->find($id);

        if (empty($page)) {
            Flash::error('Page not found');

            return redirect(route('admin.pages.index'));
        }

        return view('admin.pages.show')->with('page', $page);
    }

    /**
     * Show the form for editing the specified Page.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $page = $this->pageRepository->find($id);

        if (empty($page)) {
            Flash::error('Page not found');

            return redirect(route('admin.pages.index'));
        }

        return view('admin.pages.edit')->with('page', $page);
    }

    /**
     * Update the specified Page in storage.
     *
     * @param int $id
     * @param UpdatePageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePageRequest $request)
    {
        $input = $request->all();

        $page = $this->pageRepository->find($id);

        if($request->hasFile('image')):

            $image_rule = [
                'image' => 'image|mimes:jpg,jpeg,png|max:10240',
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

            $filename = Storage::disk('public')->putFile('pages', $input['image']);
            if($page->image != ''):
                Storage::disk('public')->delete($page->image);
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

                $page_permalink = Page::where('permalink', '=', $permalink)->get();

            } while( count($page_permalink) > 0 );

            $input['permalink'] = $permalink;
        endif;

        if (empty($page)) {
            Flash::error('Page not found');

            return redirect(route('admin.pages.index'));
        }

        $page = $this->pageRepository->update($input, $id);

        if($page->status == 1 && !$page->notified):
            /*
            $users = User::where('status', '=', 1)->get();
            foreach($users as $user):
                $user->notify(new NewsPublished($news));
            endforeach;


            $news->notified = 1;
            $news->save();
            */
        endif;

        Flash::success('Page updated successfully.');

        return redirect(route('admin.pages.index'));
    }

    /**
     * Remove the specified Page from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $page = $this->pageRepository->find($id);

        if (empty($page)) {
            Flash::error('Page not found');

            return redirect(route('admin.pages.index'));
        }

        $this->pageRepository->delete($id);

        Flash::success('Page deleted successfully.');

        return redirect(route('admin.pages.index'));
    }
}
