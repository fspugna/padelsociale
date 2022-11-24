@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Crea i gruppi
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.groups.generate']) !!}

                    <div class="form-group col-sm-6">
                        {!! Form::label('options', 'Quanti gironi vuoi creare?') !!}
                        {!! Form::number('options', 1, ['class' => 'form-control', 'min' => 1]) !!}
                    </div>

                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-lg btn-block btn-success">Genera i gruppi</button>
                    </div>

                    {!! Form::hidden('id_division', $division->id) !!}
                    
                    {!! Form::close() !!}

                    <div class="col-sm-12">
                        
                        @foreach($subscriptions as $subscription)
                        <div class="col-sm-3" style="border: 1px solid #eee">
                            <h3>{!! $subscription->team->name !!}</h3>
                            <table class="table">
                            @foreach($subscription->team->players as $player)
                            <tr>
                                <td style="width: 55px;"><img src="https://via.placeholder.com/50?text=?" class="img-circle"></td>
                                <td class="text-left">{!! $player->player->name !!} {!! $player->player->surname !!}</td>
                            </tr>
                            @endforeach
                            </table>    
                        </div>
                        @endforeach
                        
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection