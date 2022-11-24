@foreach($groups as $group)
    <div class="row" style="margin: 0; border-bottom: 1px solid #ccc; padding-top: 10px; padding-bottom: 10px">
    
        @if( $division->edit_mode == 0 )
            <input id="online-{!! $group->id !!}" type="checkbox" @if($group->flag_online) checked @endif>        
            <br>
            <label>Online</label>
            <br>

            <div class="btn-group">
                <!--a href="/admin/groups/{!! $group->id !!}/classification" class="btn btn-primary btn-group"><i class="fa fa-list"></i> Classifica</a-->
                <a href="/admin/rounds/{!! $group->id !!}/index" class="btn btn-success btn-group"><i class="fa fa-calendar"></i> Calendario</a>
            </div>        

        @else 

            <div class="btn-group">                                
                <a href="#" class="btn btn-danger" onclick="delGroup({!! $group->id !!})"><i class="fa fa-trash"></i> Elimina girone</a>                
            </div>

        @endif
        
        <h2>GIRONE {!! $group->name !!}</h2>
        
        @foreach($group->macro_teams as $team)                                         
        
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <table class="table table-striped table-bordered" >
            @if( $division->edit_mode == 1 )
            <tr>            
            {!! Form::select('team_'.$team->macroTeam->id, $groups->pluck('name', 'id'), $group->id, ['class' => 'form-control']) !!}
            </tr>
            @endif                                        
                        
            <tr>
                <td colspan="2" class="text-center"><strong> {!! $team->macroTeam->club->name !!}</strong></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center"><strong><a href="/admin/macro_teams/{!! $team->macroTeam->id !!}/show">{!! $team->macroTeam->name !!}</a></strong></td>
            </tr>
            
            
            @foreach($team->macroTeam->players as $player)
                @if( $player->starter )
                <tr>                
                    <td style="width: 55px;">
                    @php
                    $avatar = false
                    @endphp
                    @if(  count($player->player->metas) > 0 )
                        @foreach($player->player->metas as $meta)                            
                            @if($meta->meta == 'avatar' && !empty($meta->meta_value))                            
                                <img src="{!! env('APP_URL') !!}/storage/{!! $meta->meta_value !!}" class="img-circle pull-left" style="width: 40px; height: 40px;">                
                                @php
                                $avatar = true
                                @endphp
                            @endif
                        @endforeach
                        @if(!$avatar)
                            <img src="https://via.placeholder.com/40?text=?" class="img-circle pull-left">                                        
                        @endif
                    @else                            
                        <img src="https://via.placeholder.com/40?text=?" class="img-circle pull-left">                                        
                    @endif
                    </td>

                    <td class="text-left">{!! $player->player->name !!} {!! $player->player->surname !!}</td>                    

                </tr>
                @endif                
            @endforeach

            
            @if( $division->edit_mode == 1 && $teamsNotInGroup )
            <tr>
                <td colspan="2" width="100%">
                    <table style="width: 100%">
                        <tr>
                            <td width="90%">
                                <select name="substitute_{!! $team->macroTeam->id !!}" id="substitute" class="form-control">
                                    <option value="">Sostituzione</option>
                                    @foreach($teamsNotInGroup as $team_nig)
                                    <option value="{!! $group->id !!}_{!! $team_nig->team->id !!}">con {!! $team_nig->team->name !!}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="submit" name="btn_substitute" value="substitute" class="btn btn-primary">OK</button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @endif

            @if( $division->edit_mode == 1 )
            <tr>
                <td colspan="2">
                    
                    <button type="button" class="btn btn-danger btn-sm btn-block" onClick="removeTeamFromGroup({!! $group->id !!}, {!! $team->id_team !!});"><i class="fa fa-trash"></i> Elimina squadra</button>                        
                    
                </td>
            </tr>
            @endif
        </table>
        </div>
        @endforeach
        
    
    </div>
@endforeach