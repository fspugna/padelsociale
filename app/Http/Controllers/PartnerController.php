<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Partner;
use App\Models\City;
use App\Models\User;

class PartnerController extends Controller
{
    public function index(Request $request){

        $search = '';

        if( $request->query('search') !== null ):

            $search = $request->query('search');

            $partners = Partner::whereHas('user', function($query){
                                    $query->where('status', 1);
                                })
                                ->where('name', 'like', '%'.$search.'%')
                                ->orderBy('name', 'ASC')->get();

        else:

            $partners = Partner::whereHas('user', function($query){
                                    $query->where('status', 1);
                                })
                                ->orderBy('name', 'ASC')->get();

        endif;

        $cities = City::orderBy('name', 'ASC')->get();

        return view('archive-partners')->with('partners', $partners)
                                       ->with('cities', $cities)
                                       ->with('search', $search)
                                       ;
    }

    public function showByCity($slug_city, Request $request){

        $search = '';

        if( $request->query('search') !== null ):

            $search = $request->query('search');

            $partners = Partner::where('name', 'like', '%'.$search.'%')
                                ->whereHas('city', function($query) use ($slug_city) {
                                    $query->where('slug', $slug_city);
                                })
                                ->whereHas('user', function($query){
                                    $query->where('status', 1);
                                })
                                ->orderBy('name', 'ASC')->get();

        else:
            $partners = Partner::whereHas('city', function($query) use ($slug_city) {
                                    $query->where('slug', $slug_city);
                                })
                                ->whereHas('user', function($query){
                                    $query->where('status', 1);
                                })
                                ->orderBy('name', 'ASC')
                                ->get();

        endif;                                

        $cities = City::orderBy('name', 'ASC')->get();

        return view('archive-partners')->with('partners', $partners)
                                       ->with('cities', $cities)
                                       ->with('selected_city', $slug_city)
                                       ->with('search', $search)
                                       ;
    }

    public function show($id_partner){
        
        $partner = Partner::where('id', '=', $id_partner)->first();
        $user = User::where('id', '=', $partner->id_user)->first();     
        
        $metas = [];
        foreach($user->metas as $meta):
            $metas[$meta->meta] = $meta->meta_value;
        endforeach;
        
        return view('single-profilo-partner')
                    ->with('partner', $partner)
                    ->with('user', $user)                    
                    ->with('metas', $metas)
                    ;
    }
}
