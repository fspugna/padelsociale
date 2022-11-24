@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Utenti</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="box box-primary">
            <div class="box-body">
                <form action="" class="form-inline" style="padding: 20px">

                    <div class="row" style="margin-bottom: 10px">
                        <div class="form-group">
                            <label for="filter-gender">Sesso</label>
                            <select id="filter-gender" name="filter-gender" class="form-control">
                                <option value="" @if($filter_gender=='') selected @endif>TUTTI</option>
                                <option value="m" @if($filter_gender=='m') selected @endif>Maschio</option>
                                <option value="f" @if($filter_gender=='f') selected @endif>Femmina</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="filter-role">Ruolo</label>
                            <select id="filter-role" name="filter-role" class="form-control">
                                <option value="player" @if($filter_role=='player') selected @endif>Giocatore</giocate>
                                <option value="club" @if($filter_role=='club') selected @endif>Circolo</giocate>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="filter-city">Città</label>
                            {!! Form::select('filter-city', $cities, $filter_city, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('filter-club', 'Circolo' ) !!}
                            {!! Form::select('filter-club', [null=>'Seleziona circolo']+$clubs, $filter_club, ['class' => 'form-control']) !!}
                        </div>

                    </div>

                    <div class="row" style="margin-bottom: 10px">
                        <div class="form-group">
                            <label for="filter-email">Email</label>
                            <input type="text" class="form-control" name="filter-email" value="{!! $filter_email !!}" placeholder="Email">
                        </div>


                        <div class="form-group">
                            <label for="filter-mobile-phone">Telefono</label>
                            <input type="text" class="form-control" name="filter-mobile-phone" value="{!! $filter_mobile_phone !!}" placeholder="Telefono">
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="filter-status">Stato</label>
                            <select id="filter-status" name="filter-status" class="form-control">
                                <option value="attivo" @if($filter_status=='attivo') selected @endif>Attivo</giocate>
                                <option value="disabilitato" @if($filter_status=='disabilitato') selected @endif>Disabilitato</giocate>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="q">Nome</label>
                            <input type="text" class="form-control" name="q" value="{!! $q !!}" placeholder="Nome o cognome">
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Cerca</button>

                        @if($filtro_attivo)
                        <a href="/admin/users" class="btn btn-sm btn-danger"><i class="fa fa-close"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.users.table')
            </div>
        </div>

        {!! $users->setPath('/admin/users')->appends(Request::except('page'))->render() !!}

    </div>
@endsection

