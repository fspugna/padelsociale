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
        <p class="login-box-msg">Digita l'indirizzo Email per il quale vuoi effettuare il reset della password</p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif


        <div class="reset-form">
            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
                <input type="hidden" id="base_url" value="{!! env('APP_URL') !!}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button id="reset_btn" type="button" class="btn btn-primary pull-right">
                        <i class="fa fa-btn fa-envelope"></i> Reset password
                    </button>
                </div>
            </div>
        </div>

        <div id="message" class="hidden"></div>

    </div>
    <!-- /.login-box-body -->
</div>

<!-- /.login-box -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>

<script>
$("#reset_btn").click(function(){
    var email = $('input[type="email"').val();
    var base_url = $("#base_url").val();
    $.ajax({
        url: base_url+ '/api/password/create',
        data: {
            email: email
        },
        type: 'POST',
        beforeSend: function(request) {
            request.setRequestHeader("Access-Control-Allow-Headers", '*');
        },
        success: function(data){
            if(data.message !== ''){

                $("#message").html('<br><div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Ok!</h4>'+data.message+'<br><a href="/">Ritorna alla Homepage</a></div>');

            }else{

                $("#message").html('<br><div class="alert alert-error alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Errore</h4>Si è verificato un errore. Riprova più tardi.<br><a href="/">Ritorna alla Homepage</a></div>');

            }

            $("#message").removeClass("hidden");
            $(".reset-form").addClass("hidden");
        }
    })
});
</script>

</body>
</html>
