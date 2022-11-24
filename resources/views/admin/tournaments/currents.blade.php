@extends('admin.layouts.app')

@section('css')
<style>
.card-tournament{
    margin: 10px;
    border: 1px solid #999;
    background-color: #fff;
    border-radius: 5px;
    color: #000;
}

</style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            {!! trans('labels.cur_tournaments') !!}
        </h1>
    </section>
    <div class="content">                

        
        <div class="row">

            @if($currents)            
                @foreach($currents as $current)                   
                    @if(isset($current['division']))                                
                    <div class="col-sm-3 card-tournament">
                        <h3 class="text-center">{!! $current['subscription']->tournament->edition->edition_name !!}</h3>
                        <p class="text-center">{!! $current['subscription']->tournament->name !!}</p>
                        <p class="text-center"><small>{!! $current['subscription']->tournament->date_start->format('d/m/Y') !!} - {!! $current['subscription']->tournament->date_end->format('d/m/Y') !!}</small></p>
                        <table class="table">
                            <tr>
                                <td class="text-right"><strong>{!! trans('labels.zone') !!}</strong></td>
                                <td>{!! $current['subscription']->zone->city->name !!} - {!! $current['subscription']->zone->name !!}</td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>{!! trans('labels.category_type') !!}</strong></td>
                                <td>{!! $current['subscription']->categoryType->name !!}</td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>{!! trans('labels.category') !!}</strong></td> 
                                <td>{!! $current['subscription']->category->name !!}</td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>{!! trans('labels.group') !!}</strong></td>                                 
                                <td>{!! $current['group']->name !!}</td>
                            </tr>
                        </table>
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <a href="/admin/tournaments/{!! $current['subscription']->tournament->id !!}/myteam" type="button" class="btn btn-primary"><i class="fa fa-users"></i> Squadra</a>
                            </div>
                            <!--div class="btn-group" role="group">
                                <a href="/admin/groups/{!! $current['group']->id !!}/classification" type="button" class="btn btn-warning"><i class="fa fa-list"></i> Classifica</a>
                            </div-->
                            <div class="btn-group" role="group">
                                <a href="/admin/rounds/{!! $current['group']->id !!}/index" type="button" class="btn btn-success"><i class="fa fa-calendar"></i> Calendario</a>
                            </div>                                        
                        </div>
                        <br>
                    </div>
                    @elseif(isset($current['bracket']))
                    
                    <div class="col-sm-3 card-tournament">
                        <h3 class="text-center">{!! $current['bracket']->tournament->edition->edition_name !!}</h3>
                        <p class="text-center">{!! $current['bracket']->tournament->name !!}</p>
                        <p class="text-center"><small>{!! $current['bracket']->tournament->date_start->format('d/m/Y') !!} - {!! $current['bracket']->tournament->date_end->format('d/m/Y') !!}</small></p>
                        <table class="table">                            
                            <tr>
                                <td class="text-right"><strong>{!! trans('labels.category_type') !!}</strong></td>
                                <td>{!! $current['bracket']->categoryType->name !!}</td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>{!! trans('labels.category') !!}</strong></td> 
                                <td>{!! $current['bracket']->category->name !!}</td>
                            </tr>
                            
                        </table>
                        <div class="btn-group btn-group-justified" role="group">
                            <div class="btn-group" role="group">
                                <a href="/admin/tournaments/{!! $current['bracket']->tournament->id !!}/myteam" type="button" class="btn btn-primary"><i class="fa fa-users"></i> Squadra</a>
                            </div>
                            <div class="btn-group" role="group">
                                <a class="btn btn-success" href="/admin/bracket/{!! $current['bracket']->id !!}/show">Tabellone</a>
                            </div>                            
                        </div>
                        <br>
                    </div>

                    @endif
                @endforeach                                
            @else
                <div class="col-sm-12">
                    <h3>Al momento non risultano tornei attivi</h3>
                </div>
            @endif
        </div>
        
    </div>        
@endsection