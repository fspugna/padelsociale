<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Edition;

class EditionController extends Controller
{
    public function rules($id_edition){
        
       $edition = Edition::where('id', '=', $id_edition)->first();

       $page['title'] = 'Regolamento Generale';
       $page['content'] = $edition->edition_rules;

       return view('single-page-simple')
                ->with('page', $page)                
                ;
    }
    
    public function zone_rules($id_edition){
        
       $edition = Edition::where('id', '=', $id_edition)->first();

       $page['title'] = 'Regolamento Torneo di Zona';
       $page['content'] = $edition->edition_zone_rules;

       return view('single-page-simple')
                ->with('page', $page)                
                ;
    }
    
    public function awards($id_edition){
        
       $edition = Edition::where('id', '=', $id_edition)->first();

       $page['title'] = 'Premiazione';
       $page['content'] = $edition->edition_awards;

       return view('single-page-simple')
                ->with('page', $page)                
                ;
    }
    
    
    public function zonesAndClubs($id_edition){
        
        $edition = Edition::where('id', '=', $id_edition)->first();
 
        $page['title'] = 'Zone e Circoli';
        $page['content'] = $edition->edition_zones_and_clubs;
 
        return view('single-page-simple')
                 ->with('page', $page)                
                 ;
     }
    
}
