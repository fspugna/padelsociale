<div class="col-sm-8">
    <!-- Title Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('title', 'Titolo') !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
        
        @if(isset($page))
        <input type="hidden" name="permalink" value="{!! $page->permalink !!}">
        @endif

    </div>

    <!-- Content Field -->
    <div class="form-group col-sm-12 col-lg-12">
        {!! Form::label('content', 'Contenuto') !!}
        {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
    </div>
   
</div>
<div class="col-sm-4">
    @if( isset($page) )
    <!-- Id Image Field -->
    <div class="form-group col-sm-12">
        @if($page->status == 0)
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>Bozza</h3>
                    <p>{!! $page->updated_at->format('d/m/Y') !!}<br><small>{!! $page->updated_at->format('H:i:s') !!}</small></p>
                </div>
                <div class="icon">
                    <i class="fa fa-edit"></i>
                </div>                
            </div>            
        @elseif($page->status == 1)
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>PUBBLICATO</h3>
                    <p>{!! $page->updated_at->format('d/m/Y') !!}<br><small>{!! $page->updated_at->format('H:i:s') !!}</small></p>
                </div>
                <div class="icon">
                    <i class="fa fa-check"></i>
                </div>                
            </div>            
        @endif
        
    </div>
    @endif    

    <!-- Submit Field -->
    <div class="form-group col-sm-12">        
        <button type="submit" name="submitbutton" value="draft" class="btn btn-primary">Salva come bozza</button>
        <button type="submit" name="submitbutton" value="publish" class="btn btn-success">Pubblica</button>        
    </div>     


     <!-- Id Image Field -->
     @if(!empty($page->image))
     <div class="form-group col-sm-12">
        
        <img style="width: 100%; height: 200px; background: url('{{ asset('/storage/'.$page->image) }}'); background-position: center center; background-size: cover">        
        <button type="submit" name="submitbutton" value="delimg" class="btn btn-block btn-default"><i class="fa fa-trash"></i> Rimuovi immagine</button>
     </div>
     @else
     <div class="form-group col-sm-12">
        {!! Form::label('image', 'Immagine in evidenza:') !!}
        <input type="file" name="image" id="image">
    </div>
     @endif

     
</div>
