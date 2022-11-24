@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Team Player
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'teamPlayers.store']) !!}

                        @include('admin.team_players.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
