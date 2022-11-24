<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function show($command, $param) {
        $artisan = Artisan::call($command.":".$param);
        $output = Artisan::output();
        return $output;
        /*
        $artisan = Artisan::call($command,['flag'=>$param]);
        $output = Artisan::output();
        return $output;
        */
    }
}
