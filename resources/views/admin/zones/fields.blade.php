<div class="col-sm-6">

    <!-- City Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('id_city', 'Città:') !!}
        {!! Form::select('id_city', $cities, null, ['class' => 'form-control']) !!}
    </div>

    <!-- Name Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('name', 'Nome') !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Description Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('description', 'Descrizione') !!}        
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
    
</div>

<div class="col-sm-6">
    <h3>Circoli</h3>
    <input class="form-control search-input" placeholder="Cerca circolo"> 
    <input type="hidden" id="sel_club">

    <table class="table table-striped">
        <tbody id="tbody-zone-clubs">
        @foreach($clubs as $club)
        <tr id="tr_club_{!! $club->id_club !!}">
            <td>{!! $club->club->name !!}<input type="hidden" name="id_club[]" value="{!! $club->id_club !!}"></td>
            <td><button type="button" class="btn btn-danger" onclick="removeClub({!! $club->id_club !!})"><i class="fa fa-trash-o"></i></button></td>                
        </tr>
        @endforeach
        </tbody>
    </table>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.zones.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
</div>
