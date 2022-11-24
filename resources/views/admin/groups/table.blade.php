@foreach($groups as $group)
    <div class="col-sm-3">

        <input id="online-{!! $group->id !!}" type="checkbox" @if($group->flag_online) checked @endif>        
        <br>
        <label>Online</label>
        <br>

        <div class="btn-group">
            <a href="/admin/groups/{!! $group->id !!}/classification" class="btn btn-primary btn-group"><i class="fa fa-list"></i> Classifica</a>
            <a href="/admin/rounds/{!! $group->id !!}/index" class="btn btn-success btn-group"><i class="fa fa-calendar"></i> Calendario</a>
        </div>        
        
        <h2>GIRONE {!! $group->name !!}</h2>
        
        @foreach($group->teams as $team)        

        <table class="table table-striped table-bordered" >
            <tr>            
            {!! Form::select('team_group_'.$team->id, $groups->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
            </tr>
                    
            @foreach($team->team->players as $player)
            <tr>
                <td style="width: 55px;"><img src="https://via.placeholder.com/50?text=?" class="img-circle"></td>
                <td class="text-left">{!! $player->player->name !!} {!! $player->player->surname !!}</td>
                <td>
                @if( $player->starter )
                    <i class="fa fa-check" style="color: green"></i>
                @endif
                </td>
            </tr>
            @endforeach

        </table>
        @endforeach
        
    </div>
@endforeach