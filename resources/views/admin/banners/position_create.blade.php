@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Nuova Posizione Banner</h1>        
    </section>
    
    <section  style="margin-top: 14px;">
        <div class="content">
            <form action="{{route('admin.banners.positions.store')}}" method="POST">

                @csrf

                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="position_name">Nome della Posizione</label>
                            <input type="text" id="position_name" name="position_name" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a class="btn btn-default" href="/admin/banners/positions">Annulla</a>
                    <button type="submit" class="btn btn-success">Salva</button>
                </div>
            </form>
        </div>   
    </section>
@endsection
