<div class="col-sm-12">
    <div class="box box-primary">
        <div class="box-body">
            <h3 style="display: inline-block"><i class="fa fa-flag"></i> Tornei</h3>
            <button type="button" id="show-contact-modal-button" class="btn btn-primary pull-right" onClick="addTournament()"><i class="fa fa-plus"></i> Aggiungi Torneo</button>
        
            <div class="table-responsive">
                <table class="table table-striped">  
                    <thead>
                        <tr>                            
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Inizio</th>
                            <th>Fine</th>
                            <th>Termine registrazioni</th>
                            <th class="text-right">Azioni</th>
                        </tr>
                    </thead>          
                    <tbody id="selected-tournaments">
                    @if(isset($tournaments))
                        @foreach($tournaments as $tournament)
                        
                        <tr id="tr_tournament_{!! $tournament->id !!}">                            
                            <td width="10%">
                                {!! $tournament->name !!}                            
                                <input type="hidden" name="tournaments[]" value="{!! $tournament->id !!}">
                            </td>          
                            <td width="10%">
                                {!! $tournament->tournamentType->tournament_type !!}                                                            
                            </td>          
                            <td width="10%">
                                {!! $tournament->date_start->format('d/m/Y') !!}                            
                            </td>          
                            <td width="15%">
                                {!! $tournament->date_end->format('d/m/Y') !!}                            
                            </td>          
                            <td width="15%">
                                @if(!empty($tournament->registration_deadline_date))
                                    {!! $tournament->registration_deadline_date->format('d/m/Y') !!}
                                @endif
                            </td>          
                            <td>      
                                <div class="input-group pull-right" style="width: 200px">                              
                                    <button type="button" class="btn btn-sm btn-danger pull-right" style="margin-right: 5px" onclick="deleteTournament({!! $tournament->id !!})"><i class="fa fa-trash"></i></button>                                    
                                    <button type="button" class="btn btn-sm btn-default pull-right" style="margin-right: 5px" onclick="editTournament({!! $tournament->id !!})"><i class="fa fa-edit"></i></button>
                                    @if( !$tournament->generated )                                    
                                    <button type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 5px" onclick="generateTournament({!! $tournament->id !!})" id="generateTournamentBtn"><i class="fa fa-cogs"></i> Genera torneo</button>
                                    @elseif($tournament->id_tournament_type == 1)
                                    <a href="/admin/tournaments/{!! $tournament->id !!}/subscriptions" class="btn btn-sm btn-warning pull-right" style="margin-right: 5px" title="{!! trans('labels.subscriptions') !!}"><i class="fa fa-users"></i></a>
                                    @elseif($tournament->id_tournament_type == 2)
                                    <a href="/admin/tournaments/{!! $tournament->id !!}/brackets" class="btn btn-sm btn-success pull-right" style="margin-right: 5px" title="{!! trans('labels.brackets') !!}"><i class="fa fa-window-restore"></i></a>
                                    @endif
                                </div>
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