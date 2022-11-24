@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {!! trans('labels.classification') !!}
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                
                <table class="table table-striped">
                    <thead>
                        <tr>                    
                            <td>Squadra</td>
                            <td>P.ti</td>
                            <td>G</td>
                            <td>V</td>
                            <td>P</td>
                            <td>Set V</td>
                            <td>Set P</td>
                            <td>Giochi V</td>
                            <td>Giochi P</td>
                        </tr>
                    </thead>                    
                    @foreach($classification as $c)
                    <tr>
                        <td>                        
                            <table class="table">                            
                            @foreach($c->teams->players as $player)
                                @if($player->starter)
                                    <tr>
                                        <td  class="text-left">{{ $player->player->name }} {{ $player->player->surname }}</td>
                                    </tr>                                    
                                @endif
                            @endforeach
                            </table>
                        </td>
                        <td>{!! $c->points !!}</td>
                        <td>{!! $c->played !!}</td>
                        <td>{!! $c->won !!}</td>
                        <td>{!! $c->lost !!}</td>
                        <td>{!! $c->set_won !!}</td>
                        <td>{!! $c->set_lost !!}</td>
                        <td>{!! $c->games_won !!}</td>
                        <td>{!! $c->games_lost !!}</td>
                    </tr>
                    @endforeach
                </table>
                
            </div>
        </div>
    </div>
@endsection
