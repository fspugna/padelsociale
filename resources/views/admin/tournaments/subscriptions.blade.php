@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Iscrizioni
        </h1>
    </section>
    <div class="content">
    {!! Form::open(['route' => 'admin.assigncategories']) !!}
        @foreach($subscriptions as $i => $zone)
            
            @foreach($zone as $id_zone => $zona)            
                <div class="box box-primary">
                    <div class="box-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <h1>{!! $zona['name'] !!}</h1>
                            </div>
                        </div>
                        
                        @foreach($zona['category_types'] as $id_category_type => $category_type)

                            <div class="row">
                                <div class="col-sm-11 col-sm-offset-1" style="border-bottom: 1px solid #3c8dbc">                                                               
                                    <h2>{!! $category_type['name'] !!}</h2></td>
                                </div>
                            </div>
                        
                            @foreach($category_type['categories'] as $id_category => $categories)
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-2">
                                    
                                    <h3 style="display: inline-block">{!! $categories['name'] !!} 
                                        <small>{!! count($categories['values']) !!} @if( count($categories['values']) == 1) squadra iscritta @else squadre iscritte @endif</small>
                                        
                                        @if( !empty($categories['division']) )
                                            @if($categories['division']->generated == 0 )
                                                
                                                <a href="{!! route('admin.groups.prepare', ['id_tournament' => $tournament->id , 'id_zone' => $id_zone , 'id_category_type' => $id_category_type , 'id_category' => $id_category ]) !!}" class="btn btn-primary btn-sm"><i class="fa fa-cogs"></i> Crea i gironi</a>
                                                
                                            @endif
                                        @endif
                                    </h3>

                                    
                                    @if(empty($categories['division']) || $categories['division']->generated == 0 )
                                        <a style="display: inline-block; cursor: pointer; margin-top: 20px; padding: 0 40px 0 40px; border: 1px solid #eee; border-radius: 5px; background-color: #3c8dbc; color: #fff" class="pull-right" onClick="openCloseCategories('{!! $id_zone !!}-{!! $id_category_type !!}-{!! $id_category !!}');"><i id="open-close-icon-{!! $id_zone !!}-{!! $id_category_type !!}-{!! $id_category !!}" class="fa fa-folder"></i> <span id="open-close-{!! $id_zone !!}-{!! $id_category_type !!}-{!! $id_category !!}">Apri</span></a>

                                        <div id="container-{!! $id_zone !!}-{!! $id_category_type !!}-{!! $id_category !!}" class="hidden">
                                        @foreach($categories['values'] as $k => $subscription)
                                            <div class="row">
                                                <div class="col-sm-8">                                
                                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" role="tab" id="headingOne">
                                                            <h4 class="panel-title">
                                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#team_{!! $subscription->team->id !!}" aria-expanded="true" aria-controls="collapseOne">
                                                                #{!! ($k+1) !!} - {!! $subscription->team->name !!}
                                                                </a>
                                                            </h4>
                                                            </div>
                                                            <div id="team_{!! $subscription->team->id !!}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                                <div class="panel-body">
                                                                    <table class="table table-striped" >
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        @foreach( $subscription->team->players as $player )
                                                                            <tr>
                                                                                <td style="width: 55px;"><img src="https://via.placeholder.com/50?text=?" class="img-circle"></td>
                                                                                <td class="text-left">{!! $player->player->name !!} {!! $player->player->surname !!}</td>
                                                                                <td>{!! $player->player->email !!}</td>
                                                                                <td>@if( $player->starter ) Titolare @else Riserva @endif</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                        
                                                </div>
                                                <div class="col-sm-4">                                                
                                                    {!! Form::select('subscription['.$subscription->id.']', $arr_categories, $subscription->id_category, ['class' => 'form-control']) !!}                                    
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                    @else
                                        <a href="{!! route('admin.divisions.index', ['id_division' => $categories['division']->id ]) !!}" class="btn btn-success btn-sm pull-right" style="margin: 20px 20px 0 0"><i class="fa fa-list"></i> Gironi</a>                                    
                                    @endif
                                    
                                </div>
                            </div>                            
                            @endforeach
                        @endforeach                        
                    </div>
                </div>
            @endforeach
        @endforeach

        <div class="row">
            <div class="col-sm-12">
                {!! Form::hidden('id_tournament', $tournament->id) !!}                
                {!! Form::submit('Salva', ['class' => 'btn btn-primary']) !!}
                <a href="{!! route('admin.editions.edit', ['id_edition' => $tournament->edition->id] ) !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
            </div>
        </div>
    {!! Form::close() !!}
    </div>

@endsection

@section('scripts')
<script>
function openCloseCategories (id){        
    $('#container-'+id).toggleClass('hidden');
    if( $('#container-'+id).hasClass('hidden') ){
        $('#open-close-'+id).html('Apri');
        $('#open-close-icon-'+id).removeClass('fa-folder-open');        
        $('#open-close-icon-'+id).addClass('fa-folder');        
    }else{
        $('#open-close-'+id).html('Chiudi');
        $('#open-close-icon-'+id).removeClass('fa-folder');        
        $('#open-close-icon-'+id).addClass('fa-folder-open');                
    }
}   
</script>
@endsection