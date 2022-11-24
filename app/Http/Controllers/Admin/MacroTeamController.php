<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MacroTeam;
use App\Models\MacroSubscription;
use App\Models\Tournament;
use App\Models\EditionClub;
use App\Models\MacroTeamPlayer;

use Flash;

class MacroTeamController extends Controller
{
    public function edit($id_macro_team){
        $macroTeam = MacroTeam::find($id_macro_team);
        
        $macroSub = MacroSubscription::where('id_team', '=', $macroTeam->id)->first();
        $tournament = Tournament::find($macroSub->id_tournament);
        $zones = $tournament->edition->zones;
        $clubs = [];
        foreach($zones as $zone):
            foreach($zone->zone->clubs as $zoneClub):
                $clubs[] = $zoneClub->club->pluck('name', 'id');
            endforeach;
        endforeach;
                
        return view('admin.macro_teams.edit')
                    ->with('macroTeam', $macroTeam)
                    ->with('clubs', $clubs)
                    ;
    }
    
    public function update(Request $request){
        
        $input = $request->all();        
        
        if( isset($input['btn_save_macro_team']) ){            
            
            $macroTeam = MacroTeam::find($input['id_macro_team']);

            $macroTeam->name = $input['name'];
            $macroTeam->id_club = $input['id_club'];
            $macroTeam->info_match_home = $input['info_match_home'];
            $macroTeam->captain = $input['captain'];
            $macroTeam->tel_captain = $input['tel_captain'];
            $macroTeam->save();

            $titolari = [];

            foreach($input as $k => $val):
                if( strpos($k, 'starter') !== FALSE ): 
                    $titolari[] = intval(explode("_", $k)[1]);
                endif;
            endforeach;                        

            foreach($macroTeam->players as $macroTeamPlayer): 
                if( in_array($macroTeamPlayer->player->id, $titolari) ): 
                    $macroTeamPlayer->starter = 1;
                else:
                    $macroTeamPlayer->starter = 0;
                endif;
                $macroTeamPlayer->save();
            endforeach;

            if( isset($input['new_player']) ):
                foreach($input['new_player'] as $new_player):
                    $macroTeamPlayer = new MacroTeamPlayer;
                    $macroTeamPlayer->id_team = $input['id_macro_team'];
                    $macroTeamPlayer->id_player = $new_player;
                    $macroTeamPlayer->starter = 1;
                    $macroTeamPlayer->save();
                endforeach;
            endif;
                            
            Flash::success('Squadra salvata correttamente');            
            return redirect('/admin/macro_teams/'.$macroTeam->id.'/show');
        }elseif( isset($input['btn_del_player']) ){            
            $val = explode("-", $input['btn_del_player']);
            MacroTeamPlayer::where('id_team', $val[0])
                            ->where('id_player', $val[1])
                            ->delete();
            Flash::success('Giocatore rimosso correttamente');
            return redirect('/admin/macro_teams/'.$val[0].'/show');
        }
        
    }
}
