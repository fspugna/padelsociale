@extends('admin.layouts.app')

@section('content')
<div class="content">
    <form method="post" action="/admin/bracket/generate">
        
        @csrf
        
        <input type="hidden" name="id_bracket" value="{!! $bracket->id !!}">
        
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="start_phase">Fase iniziale</label>
                    <select name="start_phase" class="form-control">
                        <option value="1">Finale</option>
                        <option value="2">Semifinale</option>
                        <option value="4">Quarti</option>
                        <option value="8">Ottavi</option>
                        <option value="16">Sedicesimi</option>
                    </select>
                </div>
            </div>
        </div>    

        <div class="row">
            @foreach($subscriptions as $subscription)
            <div class="col-sm-3">
                <table class="table table-striped" style="background-color: #fff">
                    <tr>
                        <th>{!! $subscription->team->name !!}</th>                    
                    </tr>

                    @foreach($subscription->team->players as $player)
                    <tr>
                        <td>
                            {!! $player->player->name !!} {!! $player->player->surname !!}
                        </td>
                    </tr>
                    @endforeach

                </table>
            </div>
            @endforeach
        </div>

        <button class="btn btn-primary">Crea Tabellone</button>
    </form>
</div>
@endsection
