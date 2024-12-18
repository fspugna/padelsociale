<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{!! config('app.name') !!}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ url('public/css') }}/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ url('public/css') }}/bootstrap-toggle.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/css') }}/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ url('public/css') }}/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('public/css') }}/AdminLTE.min.css">
    <link rel="stylesheet" href="{{ url('public/css') }}/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{ url('public/css') }}/iCheck/all.css" />

    <!-- slider -->
    <link rel="stylesheet" href="{{ url('public/css') }}/bootstrap-slider.min.css" integrity="sha256-G3IAYJYIQvZgPksNQDbjvxd/Ca1SfCDFwu2s2lt0oGo=" crossorigin="anonymous" />

    <link rel="stylesheet" href="{{ url('public/css') }}/select2.min.css">

    <!-- Ionicons -->
    {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}

    <link rel="stylesheet" href="{{ url('public/css') }}/bootstrap-datepicker3.min.css" />
    <link rel="stylesheet" href="{{ url('public/css') }}/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="{{ url('public/css') }}/daterangepicker.css" />

    <link rel="stylesheet" href="{{ url('public/css') }}/jquery.typeahead.min.css" />

    <link rel="stylesheet" href="{{ url('public/css') }}/swiper.min.css">

    @yield('css')
</head>

<body class="skin-blue-light sidebar-mini">
@if (!Auth::guest())
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="#" class="logo">
                <b>{!! config('app.name') !!}</b>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                @if($avatar !== null)
                                    <img src="{!! $avatar !!}" class="img-circle" style="width: 25px; height: 25px;">
                                @else
                                    <img src="https://via.placeholder.com/25?text=?" class="img-circle">
                                @endif
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{!! Auth::user()->name !!}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">

                                    @if($avatar !== null)
                                        <img src="{!! $avatar !!}" class="img-circle" style="width: 100px; height: 100px;">
                                    @else
                                        <img src="https://via.placeholder.com/100?text=?" class="img-circle">
                                    @endif

                                    <p>
                                        {!! Auth::user()->name !!}
                                        <small>{!! trans('labels.member_since') !!} {!! Auth::user()->created_at->format('M. Y') !!}</small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{!! route('admin.profile') !!}" class="btn btn-default btn-flat">{!! trans('labels.profile') !!}</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{!! url('/logout') !!}" class="btn btn-default btn-flat"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {!! trans('labels.signout') !!}
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        @include('admin.layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer" style="max-height: 100px;text-align: center">
            <strong>Copyright © 2019 <a href="http://madeinapp.it">MadeInApp</a>.</strong> All rights reserved.
        </footer>

    </div>
@else
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{!! url('/') !!}">
                    {!! env('APP_NAME') !!}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{!! url('/home') !!}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <li><a href="{!! url('/login') !!}">Login</a></li>
                    <li><a href="{!! url('/register') !!}">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- jQuery 3.1.1 -->
    <script src="{{ url('public/js') }}/jquery.min.js"></script>
    <script src="{{ url('public/js') }}/moment.min.js"></script>
    <script src="{{ url('public/js') }}/bootstrap.min.js"></script>

    <script src="{{ url('public/js') }}/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('public/js') }}/bootstrap-datepicker.it.min.js"></script>
    <script src="{{ url('public/js') }}/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="{{ url('public/js') }}/daterangepicker.min.js"></script>

    <script src="{{ url('public/js') }}/bootstrap-toggle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('public/js') }}/adminlte.min.js"></script>

    <script src="{{ url('public/js') }}/icheck.min.js"></script>
    <script src="{{ url('public/js') }}/bootstrap-slider.min.js" integrity="sha256-oj52qvIP5c7N6lZZoh9z3OYacAIOjsROAcZBHUaJMyw=" crossorigin="anonymous"></script>
    <script src="{{ url('public/js') }}/select2.min.js"></script>

    <script src="{{ url('public/js') }}/typeahead.bundle.min.js"></script>

    <script src="{{ url('public/js') }}/swiper.min.js"></script>

    <script>
        $(document).ready(function(){

            $('input[type="checkbox"]').each(function(){
                var self = $(this),
                label = self.next(),
                label_text = label.text();

                label.remove();
                self.iCheck({
                    checkboxClass: 'icheckbox_flat-red',
                    radioClass: 'iradio_flat-red'
                });

            });

            if( $('input[name="daterange"]').length ){
                var locale = {
                    "format": "DD/MM/YYYY",
                    "separator": " - ",
                    "applyLabel": "Applica",
                    "cancelLabel": "Annulla",
                    "fromLabel": "Da",
                    "toLabel": "A",
                    "customRangeLabel": "Custom",
                    "weekLabel": "W",
                    "daysOfWeek": [
                        "Do",
                        "Lu",
                        "Ma",
                        "Me",
                        "Gi",
                        "Ve",
                        "Sa"
                    ],
                    "monthNames": [
                        "Gennaio",
                        "Febbraio",
                        "Marzo",
                        "Aprile",
                        "Maggio",
                        "Giugno",
                        "Luglio",
                        "Agosto",
                        "Settembre",
                        "Ottobre",
                        "Novembre",
                        "Dicembre"
                    ],
                    "firstDay": 1
                };

                $('input[name="daterange"]').daterangepicker({
                    startDate: ( $("#date_start").val() != '' ) ? $("#date_start").val() : moment().format('DD/MM/YYYY'),
                    endDate: ( $("#date_end").val() != '' ) ? $("#date_end").val() : moment().format('DD/MM/YYYY'),
                    opens: 'left',
                    locale: locale
                }, function(start, end, label) {
                    setTimeout(function(){
                        $("#date_start").val( start.format('DD/MM/YYYY') );
                        $("#date_end").val( end.format('DD/MM/YYYY') );
                    }, 500);
                });

                var period = $('#daterange').val().split(' - ');
                $("#date_start").val( period[0] );
                $("#date_end").val( period[1] );
            }
        });
        </script>
    @yield('scripts')
</body>
</html>
