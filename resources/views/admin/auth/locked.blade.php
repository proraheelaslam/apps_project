<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>Metronic | User Lock Screen 2</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('theme/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{asset('theme/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->

    <link href="{{asset('theme/pages/css/lock-2.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" /> </head>
<!-- END HEAD -->

<body class="">
<div class="page-lock">
    <div class="page-logo">
        <a class="brand" href="index.html">
            <img src="{{asset('theme/pages/img/logo-big.png')}}" alt="logo" /> </a>
    </div>
    <div class="page-body">
        <img class="page-lock-img" src="{{asset('theme/pages/media/profile/profile.jpg')}}" alt="">
        <div class="page-lock-info">
            <h1>Bob Nilson</h1>
            <span class="email"> bob@keenthemes.com </span>
            <span class="locked"> Locked </span>
            <form action="{{ url('admin/login/locked') }}" class="form-inline">
                @csrf
                <div class="input-group input-medium">
                    <input type="text" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password">
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <span class="input-group-btn">
                                <button type="submit" class="btn green icn-only">
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </button>
                            </span>
                </div>
                <!-- /input-group -->
                <div class="relogin">
                    <a href="{{url('admin/login')}}"> Not Login ? </a>
                </div>
            </form>
        </div>
    </div>
    <div class="page-footer-custom"> 2014 &copy; Metronic. Admin Dashboard Template. </div>
</div>

<!-- BEGIN CORE PLUGINS -->
<script src="{{asset('theme/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->


<script src="{{asset('theme/plugins/backstretch/jquery.backstretch.min.js')}}" type="text/javascript"></script>

<script src="{{asset('theme/global/scripts/app.min.js')}}" type="text/javascript"></script>

<script src="{{asset('theme/pages/scripts/lock-2.min.js')}}" type="text/javascript"></script>

</body>

</html>