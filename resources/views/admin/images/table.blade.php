@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
@endsection

<div class="row">
    <div class="col-xs-12">        
        @if( !$flag_delete )
        <a href="/admin/images/match/{!! $id_match !!}/create" class="btn btn-primary"><i class="fa fa-upload"></i> Carica immagini</a>
        <a href="/admin/images/match/{!! $id_match !!}/delete" class="btn btn-danger"><i class="fa fa-trash"></i> Elimina immagini</a>
        @endif        
    </div>
</div>
<br>

@foreach($images as $image)    
    <div style="width: 100px; display: inline-block; text-align: center; margin-bottom: 10px;">
        @if(!$flag_delete)
        <a data-fancybox="gallery" title="" style="width: 100px; height: 100px" href="{!! url('/storage/app/'.$image->path) !!}">
        @endif
            <div alt="" class="image-card" style="width: 100px; height: 100px; display: inline-block; background:url('{!! url('/storage/app/'.$image->path) !!}'); background-size: cover;"></div>    
            @if( $flag_delete )            
                <input type="checkbox" name="del_image[]" value="{!! $image->id !!}">
            @endif
        @if(!$flag_delete)
        </a>        
        @endif
    </div>
@endforeach

@section('scripts')
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
@endsection