<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Partner;
use App\Models\City;

class PartnerController extends Controller
{
    public function index(Request $request){

        $search_name = '';
        $search_city = null;

        if( !empty($request->query('search_name')) ):
            $search_name = $request->query('search_name');
        endif;

        if( !empty($request->query('search_city')) ):
            $search_city = $request->query('search_city');            
        endif;

        if( $search_city ):
            $partners = User::where('id_role', 4)
                            ->whereHas('partners', function($partner) use ($search_name){
                                $partner->where('name', 'like', '%'.$search_name.'%');
                            })
                            ->whereHas('partners', function($partner) use ($search_city){
                                $partner->where('id_city', $search_city);
                            })
                            ->paginate(50);
        else:

            $partners = User::where('id_role', 4)
                            ->whereHas('partners', function($partner) use ($search_name){
                                $partner->where('name', 'like', '%'.$search_name.'%');
                            })                            
                            ->paginate(50);

        endif;

        $cities = City::orderBy('name')->get();

        return view('admin.partners.index')->with('partners', $partners)
                                            ->with('cities', $cities)
                                            ->with('search_name', $search_name)
                                            ->with('search_city', $search_city)
                                            ;
    }
    
    public function create(Request $request){        
        $cities = City::orderBy('name')->get();        
        
        $input['name'] = $request->session()->pull('name', '');
        $input['surname'] = $request->session()->pull('surname', '');
        $input['email'] = $request->session()->pull('email', '');
        $input['mobile_phone'] = $request->session()->pull('mobile_phone', '');
        $input['id_city'] = $request->session()->pull('id_city', '');
        $input['partner_name'] = $request->session()->pull('partner_name', '');
        $input['partner_address'] = $request->session()->pull('partner_address', '');
        $input['partner_email'] = $request->session()->pull('partner_email', '');
        $input['partner_phone'] = $request->session()->pull('partner_phone', '');        
        $input['partner_description'] = $request->session()->pull('partner_description', '');
        
        return view('admin.partners.create')->with('cities', $cities)->with('input', $input);
    }
    
    public function store(Request $request){
        $data = $request->all();        
                               
        $request->session()->put('name', $data['name']);                  
        $request->session()->put('surname', $data['surname']);                  
        $request->session()->put('email', $data['email']);  
        $request->session()->put('mobile_phone', $data['mobile_phone']);  
        $request->session()->put('id_city', $data['id_city']);  
        $request->session()->put('partner_name', $data['partner_name']);  
        $request->session()->put('partner_address', $data['partner_address']);  
        $request->session()->put('partner_email', $data['partner_email']);  
        $request->session()->put('partner_phone', $data['partner_phone']);          
        $request->session()->put('partner_description', $data['partner_description']);  

        if(isset($partner['partner_website'])):
            $request->session()->put('partner_website', $data['partner_website']);  
        endif;
        
        if($data['password'] != $data['confirm_password']):            
            return back()->withInput()->withErrors('La password di conferma non corrisponde');
        endif;

        $check_email = User::where('id_role', '=', 4)
                           ->where('email', '=', $data['email'])
                           ->first();
        if($check_email):
            return back()->withInput()->withErrors('L\'email inserita è già utilizzata');
        endif;
                        
        $user = User::create([            
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],            
            'mobile_phone' => $data['mobile_phone'],
            'password' => Hash::make($data['password']),
            'id_role' => 4
        ]);        

        if( $user ):
            
            $partner = Partner::create ([                
                'id_user' => $user->id,
                'id_city' => $data['id_city'],                
                'name' => $data['partner_name'],
                'address' => $data['partner_address'],
                'phone' => $data['partner_phone'],
                'email' => $data['partner_email'],                                
                'description' => $data['partner_email'],                
                'website' => isset($data['partner_website']) ? $data['partner_website'] : '', 
            ]);
                    

            /** Notify administrators about the new registration */
            /*
            $users = User::where('id_role', '=', 1)->get();
            foreach($users as $user_notify):
                $user_notify->notify(new NewUser($user));        
            endforeach;
            */
        
            $request->session()->forget('name');
            $request->session()->forget('surname');
            $request->session()->forget('email');
            $request->session()->forget('mobile_phone');
            $request->session()->forget('id_city');
            $request->session()->forget('partner_name');
            $request->session()->forget('partner_address');
            $request->session()->forget('partner_email');
            $request->session()->forget('partner_phone');            
            $request->session()->forget('partner_description');
            $request->session()->forget('partner_website');            
        
            return redirect(route('admin.partners.index'));
            
        endif;

    }
    
    public function actions(Request $request){
        $input = $request->except("_token");        
        switch($input['action']){                                        
            case 'elimina': $this->delete($input['id_partner']);                             
                            return redirect(route('admin.partners.index'));
                            
        }               
    }
    
    public function edit($id_partner, Request $request){        
        $partner = User::find($id_partner);
        $cities = City::orderBy('name')->get();            
        return view('admin.partners.edit')->with('partner', $partner)->with('cities', $cities);
    }
    
    public function update(Request $request){
        $input = $request->except("_token");
        
        $user = User::find($input['id_partner']);
        
        if( !empty($input['password']) ):
            if($input['password'] != $input['confirm_password']):            
                return redirect(route('admin.partners.edit', ['id_partner' => $user->id]))->withErrors('La password di conferma non corrisponde');
            endif;
        endif;

        if( $user->email !== $input['email'] ):
            $check_email = User::where('id_role', '=', 4)
                                ->where('email', '=', $input['email'])
                                ->where('id', '!=', $input['id_partner'])
                                ->first();
            if($check_email):
                return redirect(route('admin.partners.edit', ['id_partner' => $user->id]))->withErrors('L\'email inserita è già utilizzata');
            endif;
        endif;
                        
        $user->update([            
            'status' => $input['status'],
            'name' => $input['name'],
            'surname' => $input['surname'],
            'email' => $input['email'],            
            'mobile_phone' => $input['mobile_phone'],            
            'id_role' => 4
        ]);        

        if( !empty($input['password']) ):
            $user->password = Hash::make($input['password']);
            $user->save();
        endif;

        if( $user ):
            
            $partner = Partner::where('id_user', $user->id)->first();

            if($partner):
                $partner->update([                                    
                    'id_city' => $input['id_city'],                
                    'name' => $input['partner_name'],
                    'address' => $input['partner_address'],
                    'phone' => $input['partner_phone'],
                    'email' => $input['partner_email'],                                    
                    'description' => $input['partner_description'],                
                    'website' => $input['partner_website'],  
                ]);
            endif;
        endif;

        return redirect(route('admin.partners.index'));
    }

    public function delete($id_partner){                
        $user = User::find($id_partner);
        if( $user ):
            $partner = Partner::where('id_user', $user->id)->first();
            $partner->delete();
            
            $user->delete();            
        endif;        
    }
}
