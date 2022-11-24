<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\News;

class NewsController extends Controller
{
    public function showAll(Request $request){
        if ($request->has('search')) {
            $search = $request->input('search');
            $news = News::where('title',  'LIKE', '%'.$search.'%')
                        ->orWhere('content', 'LIKE', '%'.$search.'%')
                        ->orderBy('excerpt', 'desc')
                        ->orderBy(DB::raw('cast(`time` as time)'), 'DESC')       
                        ->get();
                        
        }else{
            $news = News::orderBy('updated_at', 'desc')->get();
        }         
        
        foreach($news as $n):
            $n['srcImgCardBig'] = asset('storage/' . $n->image );
            $n['srcImgCardBig2'] = asset('storage/'. $n->image );
        endforeach;

        if($news){
            return view('archive-news')->with('news', $news);
        }else{
            return redirect(route('welcome'));
        }
    }

    public function showCategory($slug, Request $request){
        if ($request->has('search')) {

            $search = $request->input('search');

            $news = DB::table('news')
                        ->join('news_categories', 'news.id_news_category', '=', 'news_categories.id')
                        ->where('news_categories.slug', '=', $slug)
                        ->where(function($query) use ($search){
                            $query->where('title', 'LIKE', '%'.$search.'%');
                            $query->orWhere('content', 'LIKE', '%'.$search.'%');                
                        })
                        ->orderBy('excerpt', 'desc')
                        ->orderBy(DB::raw('cast(`time` as time)'), 'DESC')       
                        ->get();
        }else{
            $news = DB::table('news')
                        ->join('news_categories', 'news.id_news_category', '=', 'news_categories.id')
                        ->where('news_categories.slug', '=', $slug)
                        ->orderBy('excerpt', 'desc')
                        ->orderBy(DB::raw('cast(`time` as time)'), 'DESC')       
                        ->get();
        }


        foreach($news as $n):            
            $n->srcImgCardBig = asset('storage/' . $n->image );            
            $n->srcImgCardBig2 = asset('storage/'. $n->image );
        endforeach;

        if($news){
            return view('archive-news')->with('news', $news);
        }else{
            return redirect(route('welcome'));
        }
    }

    public function showSingle($slug){        
        $news = News::where('permalink', '=', $slug)->first();
        if($news){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $raw_content = strip_tags($news->content);
            return view('single-news')->with('news', $news)
                                      ->with('actual_link', $actual_link)
                                      ->with('raw_content', $raw_content)
                                        ;            
        }else{
            return redirect(route('welcome'));
        }
    }

    public function showPage($slug){
        $news = News::where('permalink', '=', $slug)->first();
        if($news){
            return view('single-page')->with('page', $news);
        }else{
            return redirect(route('welcome'));
        }
    }
    public function archive(Request $request){
        $search = "";
        if($request->has('nome')):
            $search = $request->input('nome');
            $news = News::where("id_news_category","=","3")
                        ->where('title', 'like', '%'.$search.'%')
                        ->orWhere("id_news_category","=","1")
                        ->paginate(10);
        else:
            $news = News::where("id_news_category","=","3")
                        ->orWhere("id_news_category","=","1")->paginate(10);               
        endif;

        
        foreach($news as $n):
            $n['srcImgCardBig'] = asset('storage/' . $n->image );
            $n['srcImgCardBig2'] = asset('storage/'. $n->image );
        endforeach;
        if($news){
            return view('archive-news')->with('news', $news);
        }else{
            return redirect(route('welcome'));
        }
    }
}
