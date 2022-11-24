@extends('admin.layouts.app')

@section('css')
<style>
    .swiper-container{
        padding: 10px 0;
    }
    .swiper-container-horizontal>.swiper-pagination-bullets{
        bottom: 4px;
    }

    .swiper-pagination {
        bottom: 95%;
    }

    .swiper-pagination-bullet {
      width: 20px;
      height: 20px;
      text-align: center;
      line-height: 20px;
      font-size: 12px;
      color:#000;
      opacity: 1;
      background: rgba(0,0,0,0.2);      
    }
    .swiper-pagination-bullet-active {
      color:#fff;
      background: #007aff;
    }
    
</style>
@endsection

@section('content')
    <section class="content-header">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.editions.index') }}"><i class="fa fa-dashboard"></i> Edizione</a></li>
                <li><a href="{{ route('admin.subscriptions', ['id_tournament' => $group->division->tournament->id]) }}">Iscrizioni</a></li>                
                <li><a href="{{ route('admin.divisions.index', ['id_division' => $group->division->id]) }}">Gironi</a></li>                
            </ol>            
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-center">            
                    Gironi                 
                    {!! $group->division->category->name !!}
                    {!! $group->division->categoryType->name !!}        
                    {!! $group->division->zone->city->name !!}
                    {!! $group->division->zone->name !!}     
                </h1> 
            </div>
        </div>   
    </section>

    <div class="content">
                        
        <div class="clearfix"></div>

        @include('flash::message')                

        <div class="clearfix"></div>

        <div class="row" style="margin-bottom: 20px">
            <div class="col-sm-12 text-center">
                <div class="btn-group" role="group">
                    @if( isset($rounds[0]) )
                    <a href="/admin/groups/{!! $rounds[0]->group->id !!}/editRounds" class="btn btn-warning btn-sm"><i class="fa fa-calendar"></i>  Modifica giornate</a>                
                    @endif

                    <a href="/admin/groups/{!! $id_group !!}/addRound" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Aggiungi giornata</a>
                </div>
            </div>
        </div>                
        
        <div class="row">
                            
            @foreach($rounds as $idxRound => $round)    

            <div class="col-lg-6">
                <div class="table-responsive">                       
                    <table class="table table-striped">                                                                            
                        <tr style="background-color: lightblue">                                
                            <th colspan="3" class="text-center">
                                Giornata {!! $round->name !!}
                                <br>
                                {!! $round->description !!}
                                <br>                       
                                
                                <table class="table">
                                @foreach($round->macro_matches as $macroMatch)
                                <tr>
                                    <td>{!! $macroMatch->team1->name !!}</td>
                                    <td>{!! $macroMatch->team2->name !!}</td>
                                </tr>
                                @endforeach
                                </table>
                                
                                <div class="btn-group" role="group">                                                                            
                                    <a href="/admin/round/{!! $round->id !!}/matches" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> Dettaglio Incontri</a>
                                    <a href="/admin/rounds/{!! $round->id !!}/delete" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Rimuovi Giornata</a>
                                </div>
                            </th>
                        </tr>                        

                    </table>                
                </div>                                        
            </div>    
            @endforeach

        </div>
    </div>
           
@endsection

