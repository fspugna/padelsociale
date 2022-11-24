@extends('admin.layouts.app')

@section('css')
<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection

@section('content')

    <div style="margin: 10px;">                       
    @if($errors->any())                
        @foreach($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Si è verificato un errore:</strong> {{$error}}                        
            </div>
        @endforeach
    @endif
    </div>
            
    <section class="content-header">
        <h1 class="pull-left">Nuovo Partner</h1>        
    </section>
    
    <section  style="margin-top: 14px;">
        <div class="content">                       

            <form action="{{route('admin.partners.update')}}" method="POST">

                @csrf
                
                <div class="box box-primary">
                    <div class="box-body">
                        <h3>Dati utente</h3>
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label for="name">Nome *</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{$partner->name}}" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="surname">Cognome *</label>
                                <input type="text" id="surname" name="surname" class="form-control"  value="{{$partner->surname}}" required>
                            </div>                        
                            <div class="col-lg-6 form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" class="form-control"  value="{{$partner->email}}" required>
                            </div>          
                            <div class="col-lg-6 form-group">
                                <label for="mobile_phone">Cellulare *</label>
                                <input type="text" id="mobile_phone" name="mobile_phone" class="form-control"  value="{{$partner->mobile_phone}}" required>
                            </div>                                              
                            <div class="col-lg-6 form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="0" @if($partner->status == 0) selected @endif>Disabilitato</option>)
                                    <option value="1" @if($partner->status == 1) selected @endif>Abilitato</option>)
                                </select>                                
                            </div> 
                            <div class="col-lg-12 form-group">
                                * Campi obbligatori
                            </div>
                        </div>                            
                    </div>
                </div>                               
                
                                
                <div class="box box-primary">
                    <div class="box-body">
                        <h3>Cambia password</h3>
                        <div class="row">
                
                            <div class="col-lg-6 form-group">
                                <label for="password">Password *</label>
                                <input type="password" id="password" name="password" class="form-control" >
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="confirm_password">Conferma password *</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                            </div>
                            <div class="col-lg-12 form-group">
                                Compilare entrambi i campi solamente se si desidera cambiare la password
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="box box-primary">
                    <div class="box-body">
                        <h3>Dati Partner</h3>
                        <div class="row">                            
                            <div class="col-lg-6 form-group">
                                <label for="partner_name">Nome Partner *</label>
                                <input type="text" id="partner_name" name="partner_name" class="form-control"  value="{{$partner->partners->first()->name}}" required>
                            </div>       
                            <div class="col-lg-6 form-group">
                                <label for="id_city">Città</label>
                                <select class="form-control" id="id_city" name="id_city">
                                    <option value="" @if($partner->partners->first()->id_city == '') selected @endif>Seleziona...</option>
                                    @foreach($cities as $city)
                                    <option value="{{$city->id}}" @if($partner->partners->first()->id_city == $city->id) selected @endif>{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>                            
                            <div class="col-lg-6 form-group">
                                <label for="partner_address">Indirizzo</label>
                                <input type="text" id="partner_address" name="partner_address" class="form-control" value="{{$partner->partners->first()->address}}">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="partner_email">Email</label>
                                <input type="email" id="partner_email" name="partner_email" class="form-control" value="{{$partner->partners->first()->email}}">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="partner_phone">Telefono</label>
                                <input type="text" id="partner_phone" name="partner_phone" class="form-control" value="{{$partner->partners->first()->phone}}">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="partner_website">Sito web</label>
                                <input type="text" id="partner_website" name="partner_website" class="form-control"  value="{{$partner->partners->first()->website}}" required>
                            </div>                             
                            <div class="col-lg-12 form-group">
                                <label for="partner_description">Descrizione</label>
                                <textarea id="partner_description" name="partner_description" class="form-control">{{$partner->partners->first()->description}}</textarea>
                            </div>
                            <div class="col-lg-12 form-group">
                                * Campi obbligatori
                            </div>
                        </div>                            
                    </div>
                </div>
                <div class="text-center">

                    <input type="hidden" name="id_partner" value="{{ $partner->id }}">

                    <a class="btn btn-default" href="/admin/partners">Annulla</a>                    
                    <button type="submit" class="btn btn-success">Salva</button>
                </div>
            </form>
        </div>   
    </section>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
<script>
    $(document).ready(function(){
        $("#partner_description").summernote({height: 300});
    });
</script>
@endsection