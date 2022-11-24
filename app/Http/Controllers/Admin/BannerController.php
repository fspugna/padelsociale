<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use Flash;
use App\Models\Banner;
use App\Models\BannerCity;
use App\Models\BannerPosition;
use App\Models\BannerPositioning;
use App\Models\Partner;
use App\Models\City;
use App\Models\Edition;

use Validator;

class BannerController extends Controller
{
    public function banners(Request $request){

        $id_partner = null;
        if( !empty($request->query('id_partner')) && $request->query('id_partner') !== 'all' ):
            $id_partner = $request->query('id_partner');
        endif;

        if( $id_partner ):
            $banners = Banner::where('id_partner', $id_partner)->paginate(10);
        else:
            $banners = Banner::paginate(10);
        endif;

        $partners = Partner::whereHas('user', function($user) {
                        $user->where('status', 1);
                    })->orderBy('name')->get();
        return view('admin.banners.index')->with('banners', $banners)
                                          ->with('partners', $partners)
                                          ->with('sel_partner', $id_partner)
                                          ;
    }

    public function create(){
        $partners = Partner::whereHas('user', function($query){
                                    $query->where('status', 1);
                                })
                                ->orderBy('name')->get();
        $cities = City::orderBy('name', 'ASC')->get();
        $editions =Edition::all();
        $positions = BannerPosition::all();
        return view('admin.banners.create')->with('partners', $partners)
                                            ->with('cities', $cities)
                                            ->with('edition', $editions)
                                            ->with('positions', $positions);
    }

    public function store(Request $request){

        $input = $request->except("_token");

        if( $request->hasFile('file') ):

            $image_rule = [
                'file' => 'image|mimes:jpg,jpeg,png|max:10240',
            ];

            $image_messages = [
                'file.image' => "Il file caricato non è un'immagine",
                'file.mimes' => "L'immagine deve essere di tipo .jpg o .png",
                'file.max' => "ATTENZIONE: questo file presenta dei problemi. L'immagine supera i 10mb."
            ];

            $image_validator = Validator::make(['file' => $input['file']], $image_rule, $image_messages);
            if($image_validator->fails()){
                return back()->withErrors($image_validator);
            }

            $filename = Storage::disk('public')->putFile('banners', $input['file']);

            $banner = Banner::create([
                'id_partner' => $input['id_partner'],
                'filename' => $filename,
                'action' => $input['action'],
                'id_edition' => $input['id_edition'],
            ]);

            BannerCity::where('id_banner', $banner->id)->delete();
            if( isset($input['banner_cities']) ):
                foreach($input['banner_cities'] as $city):
                    BannerCity::create([
                        'id_banner' => $banner->id,
                        'id_city' => $city
                    ]);
                endforeach;
            endif;

            BannerPositioning::where('id_banner', $banner->id)->delete();
            if( isset($input['banner_positions']) ):
                foreach($input['banner_positions'] as $position):
                    BannerPositioning::create([
                        'id_banner' => $banner->id,
                        'id_banner_positions' => $position
                    ]);
                endforeach;
            endif;

        endif;

        return redirect(route('admin.banners.index'));
    }

    public function edit($id_banner){
        $banner = Banner::find($id_banner);
        $partners = Partner::whereHas('user', function($user) {
                                $user->where('status', 1);
                            })->orderBy('name')->get();
        $cities = City::orderBy('name', 'ASC')->get();
        $editions =Edition::all();
        $positions = BannerPosition::all();
        return view('admin.banners.edit')->with('banner', $banner)
                                            ->with('partners', $partners)
                                            ->with('cities', $cities)
                                            ->with('edition', $editions)
                                            ->with('positions', $positions)
                                            ;
    }

    public function update(Request $request){
        $input = $request->except('_token');

        $banner = Banner::find($input['id_banner']);

        $filename = '';
        if( $request->hasFile('file') ):

            $image_rule = [
                'file' => 'image|mimes:jpg,jpeg,png|max:10240',
            ];

            $image_messages = [
                'file.image' => "Il file caricato non è un'immagine",
                'file.mimes' => "L'immagine deve essere di tipo .jpg o .png",
                'file.max' => "ATTENZIONE: questo file presenta dei problemi. L'immagine supera i 10mb."
            ];

            $image_validator = Validator::make(['file' => $input['file']], $image_rule, $image_messages);
            if($image_validator->fails()){
                return back()->withErrors($image_validator);
            }

            $filename = Storage::disk('public')->putFile('banners', $input['file']);
        endif;

        if($filename !== ''):
            $banner->filename = $filename;
        endif;

        $banner->id_partner = $input['id_partner'];
        $banner->action = $input['action'];
        $banner->id_edition = $input['id_edition'];
        $banner->save();

        BannerCity::where('id_banner', $banner->id)->delete();
        if( isset($input['banner_cities']) ):
            foreach($input['banner_cities'] as $city):
                BannerCity::create([
                    'id_banner' => $banner->id,
                    'id_city' => $city
                ]);
            endforeach;
        endif;

        BannerPositioning::where('id_banner', $banner->id)->delete();
        if( isset($input['banner_positions']) ):
            foreach($input['banner_positions'] as $position):
                BannerPositioning::create([
                    'id_banner' => $banner->id,
                    'id_banner_positions' => $position
                ]);
            endforeach;
        endif;

        return redirect(route('admin.banners.index'));

    }

    public function delete(Request $request){
        $input = $request->only('id_banner');
        $id_banner = $input['id_banner'];
        if($id_banner):
            Banner::where('id', $id_banner)->first()->delete();
            return redirect(route('admin.banners.index'));
        endif;
    }

    public function bannerPositions(){
        $positions = BannerPosition::all();
        return view('admin.banners.positions')->with('positions', $positions);

    }

    public function addBannerPosition(){
        return view('admin.banners.position_create');
    }

    public function delPosition(Request $request){
        $input = $request->all();

    }

    public function storeBannerPosition(Request $request){
        $input = $request->all();
        $position = new BannerPosition;
        $position->position_name = $input['position_name'];
        $position->save();

        return redirect(route('admin.banners.positions'));
    }

    public function deleteBannerPosition(Request $request){
        $input = $request->all();
        $position = BannerPosition::find($input['btn_del_position']);
        if($position){
            $position->delete();
        }
        return redirect(route('admin.banners.positions'));
    }

    public function bannerPositionings(){
        $positions = BannerPosition::all();
        return view('admin.banners.positionings')->with('positions', $positions);
    }

    public function getPositionings($id_position){
        $positionings = BannerPositioning::where('id_banner_positions', $id_position)->get();
        $banners = Banner::all();
        echo view('admin.banners.positionings_table')->with('positionings', $positionings)
                                                     ->with('banners', $banners)
                                                     ->with('id_position', $id_position)
                                                     ->render();
    }

    public function bannerAddPositioning(Request $request){
        $input = $request->all();
        BannerPositioning::create([
            'id_banner_positions' => $input['id_position'],
            'id_banner' => $input['id_banner']
        ]);
    }

    public function bannerDelPositioning(Request $request){
        $input = $request->all();
        $id_banner = $input['id_banner'];
        $id_banner_positions = $input['id_position'];

        BannerPositioning::where('id_banner', $id_banner)
                         ->where('id_banner_positions', $id_banner_positions)
                         ->first()
                         ->delete();

    }
}
