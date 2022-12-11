    <div class="row">
        <div class="col-sm-12">

            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">

                        <div class="form-group col-sm-2 col-lg-2 col-sm-offset-2 col-lg-offset-2">
                            {!! Form::label('logo', 'Logo') !!}
                            @if(empty($edition->logo))
                                <input type="file" name="logo">
                            @else
                                <img src="{!! asset('storage/' . $edition->logo ) !!}" class="img-circle" style="width: 100%">
                                <button type="submit" name="btn_del_logo" value="del" class="btn btn-sm btn-block btn-default"><i class="fa fa-trash"></i> Rimuovi immagine</button>
                            @endif
                        </div>

                        <!-- Name Field -->
                        <div class="form-group col-sm-6 col-lg-6 col-sm-offset-2 col-lg-offset-2">
                            {!! Form::label('id_event', 'Evento') !!}
                            {!! Form::select('id_event', $events, $edition->id_event, ['class' => 'form-control']) !!}
                        </div>

                        <!-- Name Field -->
                        <div class="form-group col-sm-6 col-lg-6 col-sm-offset-2 col-lg-offset-2">
                            {!! Form::label('edition_name', 'Nome Torneo') !!}
                            {!! Form::text('edition_name', $edition->edition_name, ['class' => 'form-control']) !!}
                        </div>

                        <!-- Name Field -->
                        <div class="form-group col-sm-6 col-lg-6 col-sm-offset-2 col-lg-offset-2">
                            {!! Form::label('edition_type', 'Tipo Torneo') !!}
                            {!! Form::select('edition_type', [ 0 => 'Singolo', 1 => 'Doppio' , 2 => 'A Squadre'], $edition->edition_type, ['class' => 'form-control']) !!}
                        </div>

                        <!-- Description Field -->
                        <div class="form-group col-sm-6 col-lg-6 col-sm-offset-2 col-lg-offset-2">
                            {!! Form::label('subscription_fee', 'Quota iscrizione') !!}
                            {!! Form::text('subscription_fee', $edition->subscription_fee, ['class' => 'form-control']) !!}
                        </div>

                    </div>

                    <div class="row">

                        <!-- Description Field -->
                        <div class="form-group col-sm-6 col-lg-6">
                            {!! Form::label('edition_description', 'Descrizione') !!}
                            {!! Form::textarea('edition_description', $edition->edition_description, ['id' => 'edition_description', 'class' => 'form-control']) !!}
                        </div>

                        <div class="form-group col-sm-6 col-lg-6">
                            {!! Form::label('edition_zones_and_clubs', 'Zone e Circoli') !!}
                            {!! Form::textarea('edition_zones_and_clubs', $edition->edition_zones_and_clubs, ['id' => 'edition_zones_and_clubs', 'class' => 'form-control']) !!}
                        </div>

                        <!-- Edition Rules Field -->
                        <div class="form-group col-sm-6 col-lg-6">
                            {!! Form::label('edition_rules', 'Regolamento Generale') !!}
                            {!! Form::textarea('edition_rules', $edition->edition_rules, ['id' => 'edition_rules', 'class' => 'form-control']) !!}
                        </div>

                        <!-- Edition Rules Field -->
                        <div class="form-group col-sm-6 col-lg-6">
                            {!! Form::label('edition_zone_rules', 'Regolamento Torneo di Zona') !!}
                            {!! Form::textarea('edition_zone_rules', $edition->edition_zone_rules, ['id' => 'edition_zone_rules', 'class' => 'form-control']) !!}
                        </div>

                        <!-- Edition Awards -->
                        <div class="form-group col-sm-6 col-lg-6">
                            {!! Form::label('edition_awards', 'Premiazione') !!}
                            {!! Form::textarea('edition_awards', $edition->edition_awards, ['id' => 'edition_awards', 'class' => 'form-control']) !!}
                        </div>


                    </div>
                </div>
            </div>

        </div>

        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-body">
                    <h3><i class="fa fa-globe"></i> Zone</h3>

                    <div class="input-group">
                        <select name="zones-select" id="zones-select" class="form-control" title="">
                            @if(isset($zones))

                                @foreach($zones as $zone)
                                    @if( $zone->city )
                                    <option value="{!! $zone->id !!}">{!! $zone->city->name !!} - {!! $zone->name !!}</option>
                                    @endif
                                @endforeach

                            @endif
                        </select>

                        <div class="input-group-addon input-group-button" style="border: none; padding: 0 0 0 4px;">
                            <button type="button" id="show-contact-modal-button" class="btn btn-primary" onClick="addZone()"><i class="fa fa-plus"></i> Aggiungi</button>
                        </div>
                    </div>


                    <table class="table table-striped">
                        <tbody id="selected-zones">
                        @if(isset($edition->zones))
                            @foreach($edition->zones as $zone)

                                <tr id="tr_zone_{!! $zone->id_zone !!}">
                                    <td width="80%">
                                        <span id="zone_name">{!! $zone->zone->city->name !!} - {!! $zone->zone->name !!}</span>
                                        <input type="hidden" name="zones[]" value="{!! $zone->id_zone !!}">
                                    </td>
                                    <td width="10%">

                                        <button type="button" class="btn btn-sm btn-danger" onClick="removeZone({!! $zone->id_zone !!})"><i class="fa fa-trash"></i></button>

                                    </td>
                                </tr>

                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-body">
                    <h3><i class="fa fa-tags"></i> Categorie</h3>

                    <div class="input-group">
                        <select name="categories-select" id="categories-select" class="form-control" title="">
                            @if(isset($categories))
                                @foreach($categories as $category)
                                <option value="{!! $category->id !!}">{!! $category->name !!}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="input-group-addon input-group-button" style="border: none; padding: 0 0 0 4px;">
                            <button type="button" id="show-contact-modal-button" class="btn btn-primary" onClick="addCategory()"><i class="fa fa-plus"></i> Aggiungi</button>
                        </div>
                    </div>

                    <table class="table table-striped">
                        <tbody id="selected-categories">
                        @if(isset($editionCategories))
                            @foreach($editionCategories as $category)

                            <tr id="tr_category_{!! $category->id_category !!}">
                                <td width="80%">
                                    <span id="category_name">{!! $category->category->name !!}</span>
                                    <input type="hidden" name="categories[]" value="{!! $category->id_category !!}">
                                </td>
                                <td width="10%">

                                    <button type="button" class="btn btn-sm btn-danger" onClick="removeCategory({!! $category->id_category !!})"><i class="fa fa-trash"></i></button>

                                </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-body">
                    <h3><i class="fa fa-users"></i> Tipologie</h3>

                    <div class="input-group">
                        <select name="category-types-select" id="category-types-select" class="form-control" title="">
                            @if(isset($categoryTypes))
                                @foreach($categoryTypes as $categoryType)
                                <option value="{!! $categoryType->id !!}">{!! $categoryType->name !!}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="input-group-addon input-group-button" style="border: none; padding: 0 0 0 4px;">
                            <button type="button" id="show-contact-modal-button" class="btn btn-primary" onClick="addCategoryType()"><i class="fa fa-plus"></i> Aggiungi</button>
                        </div>
                    </div>


                    <table class="table table-striped">
                        <tbody id="selected-category-types">
                        @if(isset($editionCategoryTypes))
                            @foreach($editionCategoryTypes as $categoryType)

                            <tr id="tr_category_type_{!! $categoryType->id_category_type !!}">
                                <td width="80%">
                                    <span id="category_type_name">{!! $categoryType->categoryType->name !!}</span>
                                    <input type="hidden" name="category_types[]" value="{!! $categoryType->id_category_type !!}">
                                </td>
                                <td width="10%">

                                    <button type="button" class="btn btn-sm btn-danger" onClick="removeCategoryType({!! $categoryType->id_category_type !!})"><i class="fa fa-trash"></i></button>

                                </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    @if(!empty(trim($edition->edition_name)))
    <div class="row">

        @include('admin.tournaments.table')

        <div id="modal-tournament" class="modal fade" tabindex="-1" role="dialog">


            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Torneo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                    @include('admin.tournaments.fields')
                    </div>
                </div>
                <div class="modal-footer">
                    @if(isset($edition))
                    <input type="hidden" name="id_edition" id="id_edition" value="{!! $edition->id !!}">
                    @endif
                    <input type="hidden" name="id_tournament" id="id_tournament" value="">
                    <button type="button" class="btn btn-primary" onClick="saveTournament()">Salva</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{!! trans('labels.close') !!}</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->


        </div><!-- /.modal -->

    </div>
    @endif


<!-- Submit Field -->

<div class="row">
    <div class="form-group col-sm-12">
        {!! Form::submit('Salva solo dati Edizione', ['class' => 'btn btn-primary', 'id' => 'simple_save', 'name' => 'btn_save_edition']) !!}
        {!! Form::submit('Salva tutto e ricrea categorie', ['class' => 'btn btn-warning', 'id' => 'save_all',  'name' => 'btn_save_edition']) !!}
        <a href="{!! route('admin.editions.index') !!}" class="btn btn-default">{!! trans('labels.cancel'); !!}</a>
    </div>
</div>
