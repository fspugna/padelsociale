@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            La mia squadra
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body"> 
                <form action="/admin/teams/changePlayer" method="post">               
                    @csrf
                    @if( $myteam['team']->flag_change )
                        <h3>Hai già effettuato una sostituzione</h3>
                    @elseif( $can_change )
                        <h3>Puoi ancora effettuare una sostituzione</h3>
                    @endif
                    <table class="table table.striped">                    
                    @foreach($myteam['players'] as $player)                        
                    <tr>
                        <td style="width: 55px;"><img src="https://via.placeholder.com/50?text=?" class="img-circle"></td>
                        <td class="text-left">{!! $player->player->name !!} {!! $player->player->surname !!}</td>
                        <td>
                        @if( !$myteam['team']->flag_change && $can_change )   
                            <input type="checkbox" name="player_{!! $player->player->id !!}_starter"
                            @if( $player->starter )
                                checked
                            @endif
                            >
                            Titolare
                        @else
                            @if( $player->starter )
                                Titolare
                            @endif
                        @endif
                        </td>
                    </tr>
                    @endforeach
                    </table>

                    @if( !$myteam['team']->flag_change && $can_change)   
                        <input type="hidden" name="id_team" value="{!! $myteam['team']->id !!}">                                     
                        <button type="submit" class="btn btn-primary">Effettua la sostituzione</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>

</script>
@endsection