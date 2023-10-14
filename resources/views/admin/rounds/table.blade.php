<!-- Slider main container -->
<div class="swiper-container">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">

        @foreach($rounds as $round)
        <div class="swiper-slide">


            @foreach($round->matchcodes->matches as $match)

            <table class="table table-responsive" id="rounds-table" data-id-match="{{ $match->id }}" style="background: #fff; color: #000; ">
                <tbody>

                    @if( !empty($match->id_club) && $match->a_tavolino == 0 )
                    <tr>
                        <td colspan="3" class="text-center" style="border: 0">
                            <h3 style="font-size: 1em;">
                                {!! $match->date->format('d/m/Y') !!}
                                @if( $match->time )
                                Ore {!! \Carbon\Carbon::createFromFormat('H:i:s', $match->time,
                                'Europe/London')->format('H:i') !!}
                                @endif
                                - {!! \App\Models\Club::where('id', '=', $match->id_club)->first()->name !!}
                            </h3>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="3" class="text-center" style="border: 0">
                            <h3 style="font-size: 1em;"><i>{!! $round->description !!}</i></h3>
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td colspan="3" class="text-center" style="border: 0">
                            Campo
                            <select name="pitch_{!! $match->id !!}" value="{!! $round->description !!}">
                                <option value=""> / </option>
                                @for ($i = 1; $i <= 15; $i++)
                                    <option value={!! $i !!} @if($match->pitch == $i) selected @endif>{!! $i !!}</option>
                                @endfor
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center" width="45%" style="border: 0" data-id-team="{{ $match->id_team1}}">
                            <table class="table table-responsive" style="border: none;">
                                @foreach($match->team1->players as $player)
                                @if($player->starter)
                                <tr>
                                    <td class="text-right" data-id-player="{{ $player->id_player }}">{!! $player->player->name !!} {!! $player->player->surname
                                        !!}</td>
                                    <td style="width: 55px;">
                                        @php
                                        $avatar = false
                                        @endphp
                                        @if( count($player->player->metas) > 0 )
                                        @foreach($player->player->metas as $meta)
                                        @if($meta->meta == 'avatar' && !empty($meta->meta_value))
                                        <img src="{!! env('APP_URL') !!}/storage/{!! $meta->meta_value !!}"
                                            class="img-circle pull-left" style="width: 40px; height: 40px;">
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

                                </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <td colspan="2" class="text-right"><a href="javascript:void(0);"
                                            onClick="vittoriaATavolino({!! $match->id !!}, {!! $match->team1->id !!}, {!! $match->team2->id !!})"><i
                                                class="fa fa-trophy"></i> Assegna vittoria a tavolino</button></a></td>
                                </tr>
                            </table>
                        </td>
                        <td class="text-center" style="border: 0">
                            <i>VS</i>
                        </td>
                        <td class="text-center" width="45%" style="border: 0" data-id-team="{{ $match->id_team2}}">
                            <table class="table table-responsive" style="border: none;">
                                @foreach($match->team2->players as $player)
                                @if($player->starter)
                                <tr>
                                    <td style="width: 55px;">
                                        @php
                                        $avatar = false
                                        @endphp
                                        @if( count($player->player->metas) > 0 )
                                        @foreach($player->player->metas as $meta)
                                        @if($meta->meta == 'avatar' && !empty($meta->meta_value))
                                        <img src="{!! env('APP_URL') !!}/storage/{!! $meta->meta_value !!}"
                                            class="img-circle pull-left" style="width: 40px; height: 40px;">
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
                                    <td class="text-left" data-id-player="{{ $player->id_player }}">{!! $player->player->name !!} {!! $player->player->surname !!}
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                                <td colspan="2" class="text-left"><a href="javascript:void(0);"
                                        onClick="vittoriaATavolino({!! $match->id !!}, {!! $match->team2->id !!}, {!! $match->team1->id !!})"><i
                                            class="fa fa-trophy"></i> Assegna vittoria a tavolino</button></a></td>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center" colspan="3">
                            @php
                            $edition_type = 1;
                            @endphp
                            @include('admin.matches.create')
                        </td>
                    </tr>

                </tbody>
            </table>
            @endforeach

        </div>
        @endforeach
    </div>
    <!-- If we need pagination -->
    <div class="swiper-pagination"></div>

</div>

<div id="modal-schedule" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="match-schedule-form" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Programma incontro</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-sm-6">
                        {!! Form::label('match_date', 'Data') !!}
                        <div class='input-group date' id="match_date">
                            <input type='text' class="form-control" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('match_hours', 'Ora') !!}

                        <div class='input-group date' id="match_hours">
                            <input type='text' class="form-control" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>

                    </div>

                    @if(!empty($clubs))
                    <div class="form-group col-sm-12">
                        {!! Form::label('match_club', 'Circolo') !!}
                        {!! Form::select('match_club', $clubs, null, ['class' => 'form-control' , 'required' => true ])
                        !!}
                    </div>
                    @else
                    <input type="hidden" id="match_club" name="match_club" value="-1">
                    @endif
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_match" id="id_match">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                    <button type="button" onClick="javascript:scheduleForm()" class="btn btn-primary">Salva</button>
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="modal-score" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Risultato incontro</h4>
            </div>
            <div class="modal-body">
                <form id="form-score" action="{!! route('admin.scores.store') !!}" method="post" class="rotext-center">
                    @csrf
                    <table class="table">
                        @for($set=1;$set<=5;$set++) <tr>
                            <td><input id="score-team1-set-{!! $set !!}" name="score-team1-set-{!! $set !!}"
                                    type='number' style="width: 90%; margin: 0 auto; font-size: 20px"
                                    class="form-control" value="" /></td>
                            <td style="text-align: center"><small>Set<br>{!! $set !!}</small></td>
                            <td><input id="score-team2-set-{!! $set !!}" name="score-team2-set-{!! $set !!}"
                                    type='number' style="width: 90%; margin: 0 auto; font-size: 20px"
                                    class="form-control" value="" /></td>
                            </tr>
                            @endfor
                    </table>
                    <br>
                    @if($current_user->id_role == 1)
                    <div class="text-center">
                        <label for="note">Note</label>
                        <textarea name="note" id="note" class="form-control" rows="2"></textarea>
                    </div>
                    @endif
                    <br>
                    <input type="hidden" id="score-id-match" name="score-id-match">
                    <input type="hidden" id="score-id-team1" name="score-id-team1">
                    <input type="hidden" id="score-id-team2" name="score-id-team2">
                    <input id="btn-ins-score" type="submit" name="insert_score" class="btn btn-success btn-block"
                        value="INSERISCI RISULTATO">
                    <input id="btn-ins-score" type="submit" name="delete_score" class="btn btn-warning btn-block"
                        value="ELIMINA RISULTATO">
                    <input id="btn-ins-score" type="submit" name="delete_schedule" class="btn btn-danger btn-block"
                        value="ELIMINA PROGRAMMA E RISULTATO">
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
