@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tournament Type
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.tournamentTypes.show_fields')
                    <a href="{!! route('admin.tournamentTypes.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
