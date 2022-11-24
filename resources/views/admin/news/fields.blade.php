<div class="col-sm-8">
    <!-- Title Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('title', 'Titolo:') !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
        
        @if(isset($news))
        <input type="hidden" name="permalink" value="{!! $news->permalink !!}">
        @endif

    </div>


    <!-- Excerpt Field (Data) -->
    <div class="form-group col-sm-12 col-lg-12">
        {!! Form::label('excerpt', 'Data:') !!}
        {!! Form::date('excerpt', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Time Field (Data) -->
    <div class="form-group col-sm-12 col-lg-12">
        {!! Form::label('time', 'Orario:') !!}
        {!! Form::text('time', null, ['class' => 'form-control', 'size' => 5 ,'maxlength' => 10, 'placeholder' => 'oo:mm']) !!}
    </div>

    <!-- Content Field -->
    <div class="form-group col-sm-12 col-lg-12">
        {!! Form::label('content', 'Contenuto:') !!}
        {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
    </div>
   
</div>
<div class="col-sm-4">
    @if( isset($news) )
    <!-- Id Image Field -->
    <div class="form-group col-sm-12">
        @if($news->status == 0)
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>Bozza</h3>
                    <p>{!! $news->updated_at->format('d/m/Y') !!}<br><small>{!! $news->updated_at->format('H:i:s') !!}</small></p>
                </div>
                <div class="icon">
                    <i class="fa fa-edit"></i>
                </div>                
            </div>            
        @elseif($news->status == 1)
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>PUBBLICATO</h3>
                    <p>{!! $news->updated_at->format('d/m/Y') !!}<br><small>{!! $news->updated_at->format('H:i:s') !!}</small></p>
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

    <div class="form-group col-sm-12">        
        {!! Form::label('id_news_category', 'Categoria') !!}
        {!! Form::select('id_news_category', $categories, (isset($news) ? $news->id_news_category : null ), ['class' => 'form-control']) !!}
    </div>    

     <!-- Id Image Field -->
     @if(!empty($news->image))
     <div class="form-group col-sm-12">
        
        <img style="width: 100%; height: 200px; background: url('{{ asset('/storage/'.$news->image) }}'); background-position: center center; background-size: cover">        
        <button type="submit" name="submitbutton" value="delimg" class="btn btn-block btn-default"><i class="fa fa-trash"></i> Rimuovi immagine</button>
     </div>
     @else
     <div class="form-group col-sm-12">
        {!! Form::label('image', 'Immagine in evidenza:') !!}
        <input type="file" name="image" id="image">
    </div>
     @endif

     
</div>
