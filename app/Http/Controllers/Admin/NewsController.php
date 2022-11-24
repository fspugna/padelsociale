<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Repositories\NewsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\User;

use App\Notifications\NewsPublished;

use Validator;

class NewsController extends AppBaseController
{
    /** @var  NewsRepository */
    private $newsRepository;

    public function __construct(NewsRepository $newsRepo)
    {
        $this->newsRepository = $newsRepo;
    }

    /**
     * Display a listing of the News.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //$news = $this->newsRepository->all();
        $news = News::orderBy('created_at', 'DESC')->paginate(20);

        return view('admin.news.index')
            ->with('news', $news);
    }

    /**
     * Show the form for creating a new News.
     *
     * @return Response
     */
    public function create()
    {
        $categories = NewsCategory::all()->pluck('name', 'id');
        return view('admin.news.create')->with('categories', $categories);
    }

    /**
     * Store a newly created News in storage.
     *
     * @param CreateNewsRequest $request
     *
     * @return Response
     */
    public function store(CreateNewsRequest $request)
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

            $filename = Storage::disk('public')->putFile('news', $input['image']);
            $input['image'] = $filename;
        endif;

        switch($request->submitbutton){
            case 'draft': $input['status'] = 0; break;
            case 'publish': $input['status'] = 1; break;
        }

        $attempts = 1;

        $newsCategory = NewsCategory::where('id', '=', $input['id_news_category'])->first();

        do{

            $permalink = \str_slug($input['title']);

            if($attempts > 1):
                $permalink .= '_' . $attempts;
            endif;

            $attempts++;

            $news_permalink = News::where('permalink', '=', $permalink)->get();

        } while( count($news_permalink) > 0 );

        $input['permalink'] = $permalink;

        $news = $this->newsRepository->create($input);

        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        if($news->status == 1):
            /*
            $users = User::where('status', '=', 1)->get();
            foreach($users as $user):
                $user->notify(new NewsPublished($news));
            endforeach;
            */
        endif;

        Flash::success('News saved successfully.');

        return redirect(route('admin.news.index'));
    }

    /**
     * Display the specified News.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $news = $this->newsRepository->find($id);

        if (empty($news)) {
            Flash::error('News not found');

            return redirect(route('admin.news.index'));
        }

        return view('admin.news.show')->with('news', $news);
    }

    /**
     * Show the form for editing the specified News.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $news = $this->newsRepository->find($id);

        $categories = NewsCategory::all()->pluck('name', 'id');

        if (empty($news)) {
            Flash::error('News not found');

            return redirect(route('admin.news.index'));
        }

        return view('admin.news.edit')
                    ->with('news', $news)
                    ->with('categories', $categories)
                    ;
    }

    /**
     * Update the specified News in storage.
     *
     * @param int $id
     * @param UpdateNewsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNewsRequest $request)
    {
        $input = $request->all();

        $news = $this->newsRepository->find($id);

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

            $filename = Storage::disk('public')->putFile('news', $input['image']);
            if($news->image != ''):
                Storage::disk('public')->delete($news->image);
            endif;
            $input['image'] = $filename;
        endif;


        switch($request->submitbutton){
            case 'draft': $input['status'] = 0; break;
            case 'publish': $input['status'] = 1; break;
            case 'delimg': $input['image'] = null; break;
        }

        $attempts = 1;

        //if( empty($input['permalink']) ):

            $newsCategory = NewsCategory::where('id', '=', $input['id_news_category'])->first();

            do{

                $permalink = \str_slug($input['title']);

                if($attempts > 1):
                    $permalink .= '_' . $attempts;
                endif;

                $attempts++;

                $news_permalink = News::where('permalink', '=', $permalink)->get();

            } while( count($news_permalink) > 0 );

            $input['permalink'] = $permalink;
        //endif;

        if (empty($news)) {
            Flash::error('News not found');
            return redirect(route('admin.news.index'));
        }

        $news = $this->newsRepository->update($input, $id);

        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        if($news->status == 1 && !$news->notified):
            /*
            $users = User::where('status', '=', 1)->get();
            foreach($users as $user):
                $user->notify(new NewsPublished($news));
            endforeach;


            $news->notified = 1;
            $news->save();
            */
        endif;

        Flash::success('News updated successfully.');

        return redirect(route('admin.news.edit', ['id' => $news->id]));
    }

    /**
     * Remove the specified News from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $news = $this->newsRepository->find($id);

        if (empty($news)) {
            Flash::error('News not found');

            return redirect(route('admin.news.index'));
        }

        $this->newsRepository->delete($id);

        Flash::success('News deleted successfully.');

        return redirect(route('admin.news.index'));
    }
}
