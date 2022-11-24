@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Assegna punti rankings</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
                <form id="frm_sel_edition" action="" method="GET">
                    <div class="form-group">
                        {!! Form::label('id_edition', 'Torneo') !!}
                        {!! Form::select('id_edition', $editions, $selected_edition, ['class' => 'form-control' , 'required' => true ]) !!}
                    </div>
                </form>

                @if( !empty($data))

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="no-bracket">NON qualificati al tabellone</label>
                            <input type="number" min="0" id="no-bracket" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lost-8">PERSO agli OTTAVI</label>
                            <input type="number" min="0" id="lost-8" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lost-4">PERSO ai QUARTI</label>
                            <input type="number" min="0" id="lost-4" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lost-2">PERSO in SEMIFINALE</label>
                            <input type="number" min="0" id="lost-2" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lost-1">PERSO la FINALE</label>
                            <input type="number" min="0" id="lost-1" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="won-1">VINTO la FINALE</label>
                            <input type="number" min="0" id="won-1" class="form-control" value="">
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.rankings.store_macro') }}" method="POST">

                    @csrf

                    <input type="hidden" name="id_edition" value="{{ $edition->id }}">

                    @php
                    $last_zone = null;
                    $last_category_type = null;
                    $last_category = null;
                    $bgcolors = ['#fff', '#b3d9ff'];
                    $sel_color = 1;
                    @endphp

                    @foreach ($data as $row)

                        @php
                        $inizio_tabellone = null;
                        if($incontri_brackets[$row['id_zone']][$row['id_category']][$row['id_category_type']]):
                            switch( $incontri_brackets[$row['id_zone']][$row['id_category']][$row['id_category_type']] ){
                                case 8: $inizio_tabellone = 'Ottavi'; break;
                                case 4: $inizio_tabellone = 'Quarti'; break;
                                case 2: $inizio_tabellone = 'Semifinali'; break;
                                case 1: $inizio_tabellone = 'Finale'; break;
                            }
                        endif;
                        @endphp

                        @if($last_zone === null)
                        <div class="box box-primary">
                            <div class="box-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-center" colspan="6">
                                            {{ $row['zona'] }} -
                                            {{ $row['category'] }} -
                                            {{ $row['category_type'] }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" width="40%">Squadra</th>
                                        <th colspan="2">Girone</th>
                                        <th colspan="2">Tabellone ({{ $inizio_tabellone }})</th>
                                        <th rowspan="2"  width="10%">Assegna punti</th>
                                    </tr>
                                    <tr>
                                        <th>Vinte</th>
                                        <th>Perse</th>
                                        <th>Vinte</th>
                                        <th>Perse</th>
                                    </tr>
                                    </thead>
                        @elseif( ( $row['id_zone'] !== $last_zone || $row['id_category'] !== $last_category || $row['id_category_type'] !== $last_category_type) && $last_zone !== null )
                                    </table>
                                </div>
                            </div>
                            <div class="box box-primary">
                                <div class="box-body">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th class="text-center" colspan="6">
                                                {{ $row['zona'] }} -
                                                {{ $row['category'] }} -
                                                {{ $row['category_type'] }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2" width="40%">Squadra</th>
                                            <th colspan="2">Girone</th>
                                            <th colspan="2">Tabellone ({{ $inizio_tabellone }})</th>
                                            <th rowspan="2" width="10%">Assegna punti</th>
                                        </tr>
                                        <tr>
                                            <th>Vinte</th>
                                            <th>Perse</th>
                                            <th>Vinte</th>
                                            <th>Perse</th>
                                        </tr>
                                        </thead>
                        @endif

                        @if( $row['id_zone'] !== $last_zone || $row['id_category'] !== $last_category || $row['id_category_type'] !== $last_category_type)
                            @php
                            $sel_color = 1 - $sel_color;
                            @endphp
                        @endif

                        <tr style="background-color: {{ $bgcolors[$sel_color] }}">
                            <td>
                                <div class="box-header">
                                    <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $row['id_macro_team'] }}" aria-expanded="false" class="collapsed">
                                        {{ $row['macro_team_name'] }}
                                    </a>
                                    </h4>
                                </div>
                                <div id="collapse{{ $row['id_macro_team'] }}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Giocatore</th>
                                                <th>Punti</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $macro_teams_players[$row['id_macro_team']] as $giocatore )
                                            <tr>
                                                <td>
                                                    @if( isset($giocatore) )
                                                        {{ $giocatore->name }} {{ $giocatore->surname }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if( isset($giocatore) && isset($rankings[$giocatore->id]) )
                                                        {{ $rankings[$giocatore->id] }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td>{{ $row['Girone_won'] }}</td>
                            <td>{{ $row['Girone_lost'] }}</td>
                            <td>{{ $row['Tabellone_won'] }}</td>
                            <td>{{ $row['Tabellone_lost'] }}</td>
                            <td>
                                @php
                                $punti_name = $row['id_zone'].'_'.$row['id_category'].'_'.$row['id_category_type'].'_'.$row['id_macro_team'].'_G'.$row['Girone_won'].$row['Girone_lost'].'_'.$incontri_brackets[$row['id_zone']][$row['id_category']][$row['id_category_type']].'T'.$row['Tabellone_won'].$row['Tabellone_lost'];
                                @endphp
                                {!! Form::text($punti_name, $rankings[$macro_teams_players[$row['id_macro_team']][0]->id], ['id' => $punti_name, 'class' => 'form-control points']) !!}
                            </td>
                        </tr>

                        @php
                        $last_zone = $row['id_zone'];
                        $last_category = $row['id_category'];
                        $last_category_type = $row['id_category_type'];
                        @endphp

                    @endforeach
                    </table>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::hidden('id_edition', $selected_edition) !!}
                        <button type="submit" class="btn btn-lg btn-success">Salva punti</button>
                    </div>
                </form>
                @endif
            </div>

    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $("#id_edition").on("change", function(){
            $("#frm_sel_edition").submit();
        });
    });

    /*
    $(".points").on('keyup', function(){
        var cur_value = $(this).val();
        var cur_id = $(this).attr('id');
        var vals = cur_id.split('_');

        console.log("cur_id", cur_id);
        var selector = '[id ^='+vals[0]+'_'+vals[1]+'_'+vals[2]+'][id $= '+vals[4]+'_'+vals[5]+']';
        console.log("selector", selector);

        $(selector).val(cur_value);
    });
    */

    $("#no-bracket").on('keyup', function(){
        $('[id $= T]').val($(this).val());
    });

    $("#lost-8").on('keyup', function(){
        $('[id $= _8T01]').val($(this).val());
    });

    $("#lost-4").on('keyup', function(){
        $('[id $= _8T11]').val($(this).val());
        $('[id $= _4T01]').val($(this).val());
    });

    $("#lost-2").on('keyup', function(){
        $('[id $= _8T21]').val($(this).val());
        $('[id $= _4T11]').val($(this).val());
        $('[id $= _2T01]').val($(this).val());
    });

    $("#lost-1").on('keyup', function(){
        $('[id $= _8T31]').val($(this).val());
        $('[id $= _4T21]').val($(this).val());
        $('[id $= _2T11]').val($(this).val());
        $('[id $= _1T01]').val($(this).val());
    });

    $("#won-1").on('keyup', function(){
        $('[id $= _8T40]').val($(this).val());
        $('[id $= _4T30]').val($(this).val());
        $('[id $= _2T20]').val($(this).val());
        $('[id $= _1T10]').val($(this).val());
    });
</script>
@endsection
