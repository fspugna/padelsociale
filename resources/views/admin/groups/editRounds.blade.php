@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Modifica giornate Gruppo {{ $group->name }} - {!! $group->division->category->name !!} {!! $group->division->categoryType->name !!}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')


        <form method="post" action="/admin/groups/updateRounds">
            @csrf
            <input type="hidden" name="id_group" value="{!! $group->id !!}">

            <div class="row" style="margin-bottom: 5px">
            @foreach($rounds as $round)

                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-body">
                            <table class="table">
                                <tr>
                                    <td class="text-center">
                                        <strong>Giornata {!! $round->name !!}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" name="id_round_{!! $round->id !!}" value="{!! $round->description !!}" class="form-control" placeholder="Descrizione giornata">
                                    </td>
                                </tr>
                            </table>

                            <table class="table">
                            @foreach($round->matches as $match)
                                <tr>
                                    <td>
                                        <select class="form-control" name="match_{!! $match->id !!}_team1">
                                        @foreach($groupTeams as $groupTeam)
                                            @if( $edition_type == 0 )
                                                @foreach( $groupTeam->team->players as $teamPlayer )
                                                    <option value="{!! $teamPlayer->id_team !!}" @if( $teamPlayer->id_team == $match->id_team1) selected @endif>{!! $teamPlayer->player->name !!} {!! $teamPlayer->player->surname !!}</option>
                                                @endforeach
                                            @else
                                                <option value="{!! $groupTeam->id_team !!}" @if( $groupTeam->id_team == $match->id_team1) selected @endif>{!! $groupTeam->team->name !!}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </td>
                                    <td>VS</td>
                                    <td>
                                        <select class="form-control"  name="match_{!! $match->id !!}_team2">
                                        @foreach($groupTeams as $groupTeam)
                                            @if( $edition_type == 0 )
                                                @foreach( $groupTeam->team->players as $teamPlayer )
                                                    <option value="{!! $teamPlayer->id_team !!}"  @if( $teamPlayer->id_team == $match->id_team2) selected @endif>{!! $teamPlayer->player->name !!} {!! $teamPlayer->player->surname !!}</option>
                                                @endforeach
                                            @else
                                                <option value="{!! $groupTeam->id_team !!}" @if( $groupTeam->id_team == $match->id_team2) selected @endif>{!! $groupTeam->team->name !!}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                            </table>
                        </div>
                        <button type="submit" name="btn_delete_round" value="{!! $round->id !!}" class="btn btn-danger btn-block">Elimina giornata</button>
                    </div>

                </div>
            @endforeach
            </div>

            @if( $edition_type > 0 )
            <div class="row" style="background: #fff">
                <div class="col-lg-12"><h3>Elenco delle squadre iscritte</h3></div>
                @foreach($groupTeams as $groupTeam)
                    <div class="col-lg-3">
                        <table class="table table-striped" style="max-height: 300px; overflow-y: auto">
                            <thead>
                                <tr>
                                    <th>{!! $groupTeam->team->name !!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $groupTeam->team->players as $teamPlayer )
                                <tr>
                                    <td>{!! $teamPlayer->player->name !!} {!! $teamPlayer->player->surname !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
            @endif

            <button type="submit" class="btn btn-success" name="btn_save_rounds"><i class="fa fa-check"></i> Salva giornate</button>
            <button type="submit" class="btn btn-primary" name="btn_add_round" value="{!! $group->id !!}"><i class="fa fa-plus"></i> Aggiungi giornata</button>
            <a href="/admin/rounds/{!! $group->id !!}/index" class="btn btn-default">Annulla</a>

        </form>
    </div>
@endsection
