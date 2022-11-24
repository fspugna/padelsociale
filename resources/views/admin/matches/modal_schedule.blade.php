<div id="modal-schedule" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="match-schedule-form" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Programma incontro</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-sm-6">
                        {!! Form::label('match_date', 'Data') !!}
                        <div class='input-group date' id="match_date">
                            <input type='text' class="form-control" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>            
                    </div>                    
                    <div class="form-group col-sm-6">
                        {!! Form::label('match_hours', 'Ora') !!}
                                        
                        <div class='input-group date' id="match_hours">
                            <input type='text' class="form-control" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                    
                    </div>                    

                    @if(!empty($clubs))
                        <div class="form-group col-sm-12">
                            {!! Form::label('match_club', 'Circolo') !!}
                            {!! Form::select('match_club', $clubs, null, ['class' => 'form-control' , 'required' => true ]) !!}
                        </div>
                    @else
                        <input type="hidden" id="match_club" name="match_club" value="-1">
                    @endif
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_match" id="id_match">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                    <button type="button" onClick="javascript:scheduleForm()" class="btn btn-primary">Salva</button>
                </div>      
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->