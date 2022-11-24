@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Zone</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.zones.create') !!}">{!! trans('labels.add') !!}</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="box box-primary">
            <div class="box-body">
                <form id="frm-filter-city">
                {!! Form::label('filter-city', 'Città') !!}
                {!! Form::select('filter-city', $cities, $filter_city ?? null, ['id' => 'filter-city', 'class' => 'form-control']) !!}
                </form>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="box box-primary">
            <div class="box-body">
                @include('admin.zones.table')
            </div>
        </div>
        <div class="text-center">
            {{ $zones->links() }}
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $("#filter-city").on('change', function(){
            $("#frm-filter-city").submit();
        });
    });
</script>
@endsection
