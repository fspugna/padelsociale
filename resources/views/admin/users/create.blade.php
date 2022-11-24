@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Utente
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                
                    {!! Form::open(['route' => 'admin.users.store']) !!}

                        @include('admin.users.fields')

                    {!! Form::close() !!}
                
            </div>
        </div>
    </div>
@endsection
