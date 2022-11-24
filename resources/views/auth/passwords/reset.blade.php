<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{!! env('APP_NAME') !!}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}">Padel Sociale</a>
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body">

        @if( !isset($error) )
        <p class="login-box-msg">Cambia la password</p>

        <form class="form-group" method="post" action="/password/cambia">

            <input type="hidden" name="_token" value="{{ $token }}">
            <input type="hidden" id="base_url" value="{!! env('APP_URL') !!}">

            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" value="{{ $email }}" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                <span class="help-block email-help-block"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <span class="help-block password-help-block"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <span class="help-block password_confirmation-help-block"></span>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" id="reset_btn" class="btn btn-primary pull-right">
                        <i class="fa fa-btn fa-refresh"></i>Reset Password
                    </button>
                </div>
            </div>
        </form>

        <div id="message" class="hidden">
        </div>
        @else
            <div class="alert alert-error alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Errore</h4>
                Il Token non è valido, potrebbe essere scaduto. Devi effettuare una nuova richiesta di Reset Password.
                <br>
                <a href="/login">Login</a>
            </div>
        @endif
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
<script>
    $("#reset_btn").click(function(){
        var token = $('input[name="_token"]').val();
        var email = $('input[type="email"').val();
        var password = $('input[name="password"]').val();
        var password_confirmation = $('input[name="password_confirmation"]').val();
        var base_url = $("#base_url").val();

        $('input[name="email"]').parent().removeClass('has-error');
        $('.email-help-block').html('');
        $('input[name="password"]').parent().removeClass('has-error');
        $('.password-help-block').html('');
        $('input[name="password_confirmation"]').parent().removeClass('has-error');
        $('.password_confirmation-help-block').html('');

        if( email == '' ){
            $('input[name="email"]').parent().addClass('has-error');
            $('.email-help-block').html('<span>Digitare un\'email valida</span>');
            return;
        }

        if( password == '' ){
            $('input[name="password"]').parent().addClass('has-error');
            $('.password-help-block').html('<span>Digitare una password</span>');
            return;
        }

        if( password.length < 6 ){
            $('input[name="password"]').parent().addClass('has-error');
            $('.password-help-block').html('<span>La password deve essere lunga almeno 6 caratteri</span>');
            return;
        }


        if( password_confirmation == '' ){
            $('input[name="password_confirmation"]').parent().addClass('has-error');
            $('.password_confirmation-help-block').html('<span>Digitare di nuovo la password</span>');
            return;
        }


        if( password !== '' && password_confirmation !== '' && password !== password_confirmation ){
            $('input[name="password_confirmation"]').parent().addClass('has-error');
            $('.password_confirmation-help-block').html('<span>La password di conferma non può essere diversa</span>');
            return;
        }

        $.ajax({
            url: base_url+'/api/password/reset',
            data: {
                email: email,
                password: password,
                password_confirmation: password_confirmation,
                token: token
            },
            type: 'POST',
            beforeSend: function(request) {
                request.setRequestHeader("Access-Control-Allow-Headers", '*');
            },
            success: function(data){
                if(data.email == email){

                    $("#message").html('<br><div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Ok!</h4>Hai modificato con successo la tua password.<br><a href="/">Ritorna alla Homepage</a></div>');

                }else{

                    $("#message").html('<br><div class="alert alert-error alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Errore</h4>Si è verificato un errore. Riprova più tardi.<br><a href="/">Ritorna alla Homepage</a></div>');

                }

                $("#message").removeClass("hidden");
                $(".form-group").addClass("hidden");
            }
        })
    });
</script>
</body>
</html>
