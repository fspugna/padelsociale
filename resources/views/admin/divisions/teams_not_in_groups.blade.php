
<h2>GIRONE NON ASSEGNATO</h2>

<div class="row" style="margin: 0; border-bottom: 1px solid #ccc; padding-top: 10px; padding-bottom: 10px">

@foreach($teamsNotInGroup as $team)
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <table class="table table-striped table-bordered" >
            <tr>
                <td colspan="2">{!! $team->team->name !!}</td>
            </tr>
            @foreach($team->team->players as $teamPlayer)
                @if( $teamPlayer->starter )
                    <tr>
                        <td style="width: 55px;">
                            @php
                            $avatar = false
                            @endphp
                            @if(  count($teamPlayer->player->metas) > 0 )
                                @foreach($teamPlayer->player->metas as $meta)                            
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
                        <td>{!! $teamPlayer->player->name !!} {!! $teamPlayer->player->surname !!}</td>
                    </tr>
                @endif
            @endforeach
            <tr>                
                <td colspan="2">
                    <select name="team_{!! $team->team->id !!}" id="team_{!! $team->team->id !!}" class="form-control">
                        <option value="">Seleziona girone</option>
                        @foreach($groups as $group)
                        <option value="{!! $group->id !!}">{!! $group->name !!}</option>
                        @endforeach
                    </select>
                </td>                            
            </tr>                            
        </table>
    </div>
@endforeach
</div>