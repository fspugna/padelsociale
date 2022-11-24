<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Page;

class PageController extends Controller
{
    public function show($slug){
        $page = Page::where('permalink', '=', $slug)->first();
        if($page){
            return view('single-page')->with('page', $page);
        }else{
            return redirect(route('welcome'));
        }
    }    
}
