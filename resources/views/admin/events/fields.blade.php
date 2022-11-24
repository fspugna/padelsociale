<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <!-- Name Field -->
                    <div class="form-group col-sm-6 col-lg-6 col-sm-offset-2 col-lg-offset-2">
                        {!! Form::label('name', 'Nome Evento') !!}
                        {!! Form::text('name', $event->name, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="row">
    <div class="form-group col-sm-12">
        {!! Form::submit('Salva', ['class' => 'btn btn-primary', 'id' => 'save_all',  'name' => 'btn_save_event']) !!}
        <a href="{!! route('admin.events.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
    </div>
</div>
