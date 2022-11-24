<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ route('admin.home') }}">ENTRA</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Giropadel
                </div>
                
                @if($tournaments)
                    <h2>Tornei in corso</h2>
                    @foreach($tournaments as $t)                    
                        <div style="width: 100%; margin: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <h>{!! $t->name !!} {!! $t->edition->name !!}</h3>
                            <br>                                                                                    
                            
                            @if( !isset($iscritto[$t->id]) )                                
                            <a href="admin/tournaments/{{ $t->id }}/subscription" class="btn btn-primary">ISCRIVITI</a>                        
                            @endif
                            
                        </div>
                    @endforeach
                @endif

                @if($next)
                    <h2>Prossimi tornei</h2>
                    @foreach($next as $t)                    
                        <div style="width: 100%; margin: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <h>{!! $t->name !!} {!! $t->edition->name !!}</h3>
                            <br>                                                                                    
                            
                            @if( !isset($iscritto[$t->id]) )                                
                            <a href="admin/tournaments/{{ $t->id }}/subscription" class="btn btn-primary">ISCRIVITI</a>                        
                            @endif
                            
                        </div>
                    @endforeach
                @endif

                @if($news)
                    @foreach($news as $n)
                        <div>
                            <h3>{!! $n->title !!}</h3>                            
                            <small>{!! $n->excerpt !!}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </body>
</html>
