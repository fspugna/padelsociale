<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Session;

use Carbon\Carbon;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\User;
use App\Models\UserGallery;
use App\Models\UserMeta;
use App\Models\UserMetaItem;
use App\Models\Role;
use App\Models\Club;
use App\Models\UserClub;
use App\Models\Zone;
use App\Models\City;
use App\Models\Partner;

use Validator;

class UserController extends Controller
{

    public function showProfile(){

        $userMetaItems = UserMetaItem::where('meta_cat', '=', 'info')->get();
        foreach($userMetaItems as $metaItem):
            $metaTypes[$metaItem->meta_key] = $metaItem->meta_type;
            $metaValues[$metaItem->meta_key] = $metaItem->meta_values;
            $metaLabels[$metaItem->meta_key] = trans('labels.'.$metaItem->meta_key);
        endforeach;

        return view('single-profilo')
                    ->with('metaTypes', $metaTypes)
                    ->with('metaValues', $metaValues)
                    ->with('metaLabels', $metaLabels)
                    ;
    }

    public function editProfile(Request $request){
        $metaTypes = [];
        $metaValues = [];
        $metaLabels = [];
        $metaPrivate = [];
        
        $userMetaItems = UserMetaItem::where('meta_cat', '=', 'info')->get();
        foreach($userMetaItems as $metaItem):
            $metaTypes[$metaItem->meta_key] = $metaItem->meta_type;
            $metaValues[$metaItem->meta_key] = $metaItem->meta_values;
            $metaLabels[$metaItem->meta_key] = trans('labels.'.$metaItem->meta_key);
            $metaPrivate[$metaItem->meta_key] = $metaItem->private;
        endforeach;

        $clubs = Club::all();

        $user = User::where('id', '=', Auth::id())->first();

        $cities = City::orderBy('name', 'asc')->get();


        if( $user->id_role == 2 ):

            return view('edit-profilo')
                        ->with('metaTypes', $metaTypes)
                        ->with('metaValues', $metaValues)
                        ->with('metaLabels', $metaLabels)
                        ->with('metaPrivate', $metaPrivate)
                        ->with('clubs', $clubs)
                        ->with('cities', $cities)
                        ;

        elseif( $user->id_role == 3 ):

            $club = Club::where('id_user', '=', $user->id)->first();

            return view('edit-profilo-circolo')
                        ->with('user', $user)
                        ->with('club', $club)
                        ->with('cities', $cities)
                        ;

        elseif( $user->id_role == 4 ):

            $partner = Partner::where('id_user', '=', $user->id)->first();

            return view('edit-profilo-partner')
                        ->with('user', $user)
                        ->with('partner', $partner)
                        ->with('cities', $cities)
                        ;
        endif;
    }

    public function updateProfile(Request $request){

        $input = $request->all();

        $rotate = null;

        if( isset($input['btn_rotate_left']) ){

            $userMeta = UserMeta::where('id_user', '=', Auth::id())
                            ->where('meta', '=', 'avatar')
                            ->first();

            if( $userMeta ){
                $filename = asset('storage/'.$userMeta->meta_value);
                $pathinfo = pathinfo(Storage::path($userMeta->meta_value));

                $new_file = str_replace('/web/htdocs', '', str_replace('/home/storage/app/avatars/', '/public/storage/avatars/', Storage::path($userMeta->meta_value)));
                //dd(file_exists($new_file), $new_file);

                if($pathinfo['extension'] == 'jpeg' || $pathinfo['extension'] == 'jpg'){

                    $source = imagecreatefromjpeg($filename);

                    // Rotate
                    $rotate = imagerotate($source, 90, 0);
                    imagejpeg($rotate, 'storage/avatars/'.basename($userMeta->meta_value), 100);

                }elseif($pathinfo['extension'] == 'png'){
                    $source = imagecreatefrompng($filename);

                    // Rotate
                    $rotate = imagerotate($source, 90, 0);
                    imagepng($rotate, 'storage/avatars/'.basename($userMeta->meta_value), 100);

                }
                Artisan::call('cache:clear');
                Artisan::call('view:clear');
                return back()->withInput();

            }

        }

        if( isset($input['btn_rotate_right']) ){

            $userMeta = UserMeta::where('id_user', '=', Auth::id())
                            ->where('meta', '=', 'avatar')
                            ->first();

            if( $userMeta ){
                $filename = asset('storage/'.$userMeta->meta_value);
                $pathinfo = pathinfo(Storage::path($userMeta->meta_value));

                $new_file = str_replace('/web/htdocs', '', str_replace('/home/storage/app/avatars/', '/public/storage/avatars/', Storage::path($userMeta->meta_value)));
                //dd(file_exists($new_file), $new_file);

                if($pathinfo['extension'] == 'jpeg' || $pathinfo['extension'] == 'jpg'){

                    $source = imagecreatefromjpeg($filename);

                    // Rotate
                    $rotate = imagerotate($source, -90, 0);
                    imagejpeg($rotate, 'storage/avatars/'.basename($userMeta->meta_value), 100);

                }elseif($pathinfo['extension'] == 'png'){
                    $source = imagecreatefrompng($filename);

                    // Rotate
                    $rotate = imagerotate($source, -90, 0);
                    imagepng($rotate, 'storage/avatars/'.basename($userMeta->meta_value), 100);

                }

                Artisan::call('cache:clear');
                Artisan::call('view:clear');

                return back()->withInput();

            }

        }



        $user = User::where('id', '=', Auth::id())->first();

        if(isset($input['btn_submit_profile']) && $input['btn_submit_profile'] == 'save_profile'){

            if( isset($input['new_password']) && !empty($input['new_password']) ){
                if( !isset($input['confirm_new_password']) || empty($input['confirm_new_password']) ){
                    return back()->withInput()->withErrors('Digitare nuovamente la password per confermarla');
                    exit;
                }

                if( !empty($input['confirm_new_password']) && $input['confirm_new_password'] != $input['new_password'] ){
                    return back()->withInput()->withErrors('La password di conferma non corrisponde');
                    exit;
                }

                if( $input['new_password'] == $input['confirm_new_password'] ){
                    $user->password = Hash::make($input['new_password']);
                }
            }

            if($request->hasFile('profile_image')):

                $image_rule = [
                    'profile_image' => 'mimes:jpg,jpeg,png|max:10240',
                ];

                $image_messages = [
                    'profile_image.image' => "Il file caricato non è un'immagine",
                    'profile_image.mimes' => "L'immagine deve essere di tipo .jpg o .png",
                    'profile_image.max' => "ATTENZIONE: questo file presenta dei problemi. L'immagine supera i 10mb."
                ];

                $image_validator = Validator::make(['profile_image' => $input['profile_image']], $image_rule, $image_messages);
                if($image_validator->fails()){
                    return back()->withErrors($image_validator);
                }

                $file = $request->file('profile_image');

                //$img = \Image::make($request->file('profile_img')->getRealpath());
                //$img->orientate();

                //Move Uploaded File
                $filename = Storage::disk('public')->putFile('avatars', $file);
               //dd($filename);
                $userMeta = UserMeta::where('id_user', '=', Auth::id())
                                        ->where('meta', '=', 'avatar')
                                        ->first();
                if(!$userMeta){
                    $userMeta = new UserMeta;
                    $userMeta->id_user = $user->id;
                    $userMeta->meta = 'avatar';
                }

                Storage::disk('public')->delete($userMeta->meta_value);

                $userMeta->meta_value = $filename;
                $userMeta->save();
            endif;

            if($request->hasFile('gallery')):

                $files = $request->file('gallery');

                foreach($files as $file):

                    $image_rule = [
                        'gallery' => 'mimes:jpg,jpeg,png|max:10240',
                    ];

                    $image_messages = [
                        'gallery.image' => "Il file caricato non è un'immagine",
                        'gallery.mimes' => "L'immagine deve essere di tipo .jpg o .png",
                        'gallery.max' => "ATTENZIONE: questo file presenta dei problemi. L'immagine supera i 10mb."
                    ];

                    $image_validator = Validator::make(['gallery' => $file], $image_rule, $image_messages);
                    if($image_validator->fails()){
                        return back()->withErrors($image_validator);
                    }

                endforeach;


                foreach($files as $file):

                    $filename = Storage::disk('public')->putFile('galleries', $file);
                    $orig_filename = $filename;

                    $filename = asset('storage/'.$filename);

                    $pathinfo = pathinfo(Storage::path($filename));

                    //dd(file_exists($new_file), $new_file);

                    if($pathinfo['extension'] == 'jpeg' || $pathinfo['extension'] == 'jpg'):

                        if (function_exists('exif_read_data')) {
                            $exif = exif_read_data($filename);
                            if($exif && isset($exif['Orientation'])) {
                                $orientation = $exif['Orientation'];
                                if($orientation != 1){
                                    $img = imagecreatefromjpeg($filename);
                                    $deg = 0;
                                    switch ($orientation) {
                                        case 3:
                                            $deg = 180;
                                            break;
                                        case 6:
                                            $deg = 270;
                                            break;
                                        case 8:
                                            $deg = 90;
                                            break;
                                    }

                                    if ($deg) {
                                        $img = imagerotate($img, $deg, 0);
                                    }

                                    // then rewrite the rotated image back to the disk as $filename
                                    imagejpeg($img, $filename, 95);
                                } // if there is some rotation necessary
                            } // if have the exif orientation info
                        } // if function exists


                    endif;


                    $userGallery = new UserGallery;
                    $userGallery->id_user = $user->id;
                    $userGallery->filename = $orig_filename;
                    $userGallery->save();
                endforeach;

            endif;

            //dd($id_city);
            if( !empty($input['email']) ):
                if( $user->email != $input['email'] ){
                    $check_email = User::where('id', '!=', Auth::id() )
                                    ->where('email', '=', $input['email'])
                                    ->where('id_role', $user->id_role)
                                    ->first();
                    if( $check_email ):
                        return back()->withInput()->withErrors('L\'email inserita è già utilizzata');
                        exit;
                    endif;
                }
            endif;

            if( !empty($input['mobile_phone']) ):
                if( $user->mobile_phone != $input['mobile_phone'] ){
                    $check_mobile_phone = User::where('id', '!=', Auth::id() )
                                            ->where('mobile_phone', '=', $input['mobile_phone'])
                                            ->where('id_role', $user->id_role)
                                            ->first();
                    if( $check_mobile_phone ):
                        return back()->withInput()->withErrors('Il cellulare inserito è già utilizzato');
                        exit;
                    endif;
                }
            endif;

            $user->name = $input['name'];
            $user->surname = $input['surname'];
            $user->email = $input['email'];
            $user->mobile_phone = $input['mobile_phone'];

            $user->id_city = isset($input['id_city']) ? $input['id_city'] : null;

            if($user->id_role==2):
                $user->gender = $input['gender'];

                if(isset($input['id_club']))
                    $user->id_club = $input['id_club'];

                $user->lun = $input['lunedi'];
                $user->mar = $input['martedi'];
                $user->mer = $input['mercoledi'];
                $user->gio = $input['giovedi'];
                $user->ven = $input['venerdi'];
                $user->sab = $input['sabato'];
                $user->dom = $input['domenica'];
                $user->note_disp = $input['note_disp'];

            endif;

            $user->save();

            if($user->id_role==3):
                $club = Club::where('id_user', '=', $user->id)->first();
                if(!$club):
                    $club = new Club;
                    $club->id_user = $user->id;
                endif;
                $club->name = $input['club_name'];
                $club->id_city = $input['id_city'];
                $club->address = $input['address'];
                $club->phone = $input['phone'];
                $club->mobile_phone = $input['mobile_phone'];
                $club->description = $input['description'];
                $club->save();

                Session::flash('message', 'Il profilo è stato modificato');
                return redirect(route('clubs.show', ['id_club' => $club->id]));
            endif;

            if($user->id_role==4):

                $partner = Partner::where('id_user', '=', $user->id)->first();
                if(!$partner):
                    $partner = new Partner;
                    $partner->id_user = $user->id;
                endif;
                $partner->name = $input['club_name'];
                $partner->id_city = isset($input['id_city']) ? $input['id_city'] : null;
                $partner->address = $input['address'];
                $partner->phone = $input['phone'];
                $partner->description = $input['description'];
                $partner->website = $input['website'];
                $partner->save();

                Session::flash('message', 'Il profilo è stato modificato');
                return redirect(route('partner.show', ['id_partner' => $partner->id]));
            endif;

            if($user->id_role==2):
                UserClub::where('id_user', '=', $user->id)->delete();
                if(isset($input['userClubs'])):
                    foreach($input['userClubs'] as $id_club):
                        $userClub = new UserClub;
                        $userClub->id_user = $user->id;
                        $userClub->id_club = $id_club;
                        $userClub->save();
                    endforeach;
                endif;

                $userMetaItems = UserMetaItem::all()->pluck('meta_key');

                foreach($userMetaItems as $metaItem):
                    if(array_key_exists($metaItem, $input)):

                        $userMeta = UserMeta::where('id_user', '=', Auth::id())
                                                ->where('meta', '=', $metaItem)
                                                ->first();
                        if(!$userMeta){
                            $userMeta = new UserMeta;
                            $userMeta->id_user = $user->id;
                            $userMeta->meta = $metaItem;
                        }

                        $userMeta->meta_value = $input[$metaItem];
                        $userMeta->save();
                    endif;
                endforeach;

                Session::flash('message', 'Il profilo è stato modificato');
                return redirect(route('players.show', ['id_player' => Auth::id()]));

            endif;

        }elseif(isset($input['btn_submit_profile']) && $input['btn_submit_profile'] == 'delete_avatar'){
            $userMeta = UserMeta::where('id_user', '=', Auth::id())
                                ->where('meta', '=', 'avatar')
                                ->first();

            if( $userMeta ):
                Storage::disk('public')->delete($userMeta->meta_value);
                $userMeta->delete();
            endif;

            return redirect(route('players.show', ['id_player' => Auth::id()]));
        }
    }

    public function deleteImage($id){
        UserGallery::find($id)->delete();
    }
}