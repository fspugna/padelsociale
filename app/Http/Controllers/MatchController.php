<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Image;

class MatchController extends Controller
{

    public function images($id_match){
        $images = Image::where('id_match', '=', $id_match)->get();
        return view('page-images')->with('images');
    }
    
}
