<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Division;
use App\Models\Tournament;
use App\Models\Zone;
use App\Models\Group;

class DivisionController extends Controller
{
     

    public function show($id_division){
        $groups = Group::where('id_division', '=', $id_division)
                        ->where('flag_online', '=', 1)
                        ->get();        
        return view('single-categoria')->with('groups', $groups);
    }
}