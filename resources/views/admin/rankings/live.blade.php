@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Rankings Live</h1>        
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
              
                <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-striped">
                        <tr>
                            <th>Posizione</th>
                            <th colspan="2" >Giocatore</th>
                            <th>Punti</th>
                        </tr>
                        @foreach($rankings as $i => $ranking)
                            @if($ranking->player->id == Auth::id() )
                            <tr style="background: lightblue">
                            @else
                            <tr>
                            @endif
                                <td style="width: 10px">{!! $i+1 !!}</td>                                                                        
                                <td style="width: 30px">                                      
                                    @if(  count($ranking->player->metas) > 0 )
                                        @foreach($ranking->player->metas as $meta)                            
                                            @if($meta->meta == 'avatar')                            
                                                <img src="{!! env('APP_URL') !!}/storage/{!! $meta->meta_value !!}" class="img-circle pull-left" style="width: 40px; height: 40px;">                
                                            @endif
                                        @endforeach
                                    @else                            
                                        <img src="https://via.placeholder.com/40?text=?" class="img-circle pull-left">                                        
                                    @endif                                    
                                </td>
                                <td>
                                    {!! $ranking->player->name !!} {!! $ranking->player->surname !!}
                                    <br>
                                    <small>{!! $ranking->player->email !!}</small>
                                </td>
                                <td>
                                    <span style="font-size: 30px">{!! $ranking->points !!}</span>
                                </td>
                            </tr>
                        @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection