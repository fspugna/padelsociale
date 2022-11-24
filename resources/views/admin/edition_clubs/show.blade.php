@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Edition Club
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.edition_clubs.show_fields')
                    <a href="{!! route('editionClubs.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
