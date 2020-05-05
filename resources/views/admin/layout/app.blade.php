<!DOCTYPE html>
<!--
Template Name: Nextneighbour
Version: 1.0.0
-->
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>Nextneighbour | Dashboard</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('theme/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('theme/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('theme/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('theme/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('theme/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('theme/global/css/components.min.css')}}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{asset('theme/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('theme/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('theme/layouts/layout/css/themes/darkblue.min.css')}}" rel="stylesheet" type="text/css"
          id="style_color"/>
    <link href="{{asset('theme/layouts/layout/css/layout.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('theme/global/plugins/bootstrap-summernote/summernote.css')}}">

    <link rel="stylesheet" href="{{asset('theme/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}">
    <link rel="stylesheet" href="{{asset('theme/pages/css/profile.css')}}">

    <link rel="stylesheet" href="{{asset('theme/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}">

    <link href="{{asset('theme/pages/css/blog.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('theme/global/plugins/cubeportfolio/css/cubeportfolio.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/pages/css/portfolio.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('theme/css/neighborhood.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/apps/css/todo-2.min.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link href=" {{asset('theme/layouts/layout/css/custom.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="{{asset('theme/layouts/layout/img/favicon.ico')}}"/>

    <!-- for select 2-->
    <link href="{{asset('theme/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('theme/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- for select 2-->

    <link href="{{asset('theme/global/plugins/ladda/ladda-themeless.min.css')}}" rel="stylesheet" type="text/css">


    <!-- for date range-->
    <link href="{{ asset("theme/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("theme/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("theme/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("theme/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- for date range-->



</head>
<!-- END HEAD -->
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white  {{ Session::get('bodySidebarState') }}">
    {{-- page-sidebar-closed --}}
<script>
    var baseUrl = "{{url('/')}}";
    var datatableLoader = "{{ asset("theme/images/loading_spinner.gif") }}";
</script>
<!-- Header -->
@include('admin.layout.header')
<!-- End Header -->


<div class="clearfix"></div>
<div class="page-container">
    <!-- Sidebar -->
    @include('admin.layout.sidebar')
    <!-- Sidebar -->
    <div class="page-content-wrapper">
        <!-- CONTENT -->
        @yield('content')
    </div>
    <!-- END CONTENT -->
</div>
<div class="page-footer">
    <div class="page-footer-inner"> 2019 &copy; Nextneighbour
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->

<script src="{{asset('theme/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js" integrity="sha256-UiqIqgNXwR8ChFMaD8VrY0tBUIl/soqb7msaauJWZVc=" crossorigin="anonymous"></script>
<script src="{{asset('theme/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}"
        type="text/javascript"></script>

<script src="{{asset('theme/global/scripts/datatable.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{ asset('theme/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/pages/scripts/table-datatables-ajax.min.js') }}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}"
        type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>

<script src="{{asset('theme/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}"
        type="text/javascript"></script>


<script src="{{asset('theme/global/plugins/morris/morris.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/morris/raphael-min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/counterup/jquery.waypoints.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/counterup/jquery.counterup.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/jquery.sparkline.min.js')}}" type="text/javascript"></script>
{{--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZ3RNzS1bJxTIULXIHjEVCHW_uN8crF-8"></script>--}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDc0WtGMVywd1ATs0GFDrMwx8UyNw7y7ic&libraries=drawing,places"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha256-3blsJd4Hli/7wCQ+bmgXfOdK7p/ZUMtPXY08jmxSSgk=" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script src="{{asset('theme/global/plugins/bootstrap-summernote/summernote.min.js')}}"></script>


<script src="{{asset('theme/global/scripts/app.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/pages/scripts/dashboard.min.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-loading-overlay/2.1.6/loadingoverlay.min.js" integrity="sha256-CImtjQVvmu/mM9AW+6gYkksByF4RBCeRzXMDA9MuAso=" crossorigin="anonymous"></script>
<script src="{{asset('theme/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}"></script>
<script src="{{asset('theme/pages/scripts/profile.js')}}"></script>


<script src="{{asset('theme/global/plugins/cubeportfolio/js/jquery.cubeportfolio.min.js')}}" type="text/javascript"></script>

<script src="{{asset('theme/pages/scripts/portfolio-1.js')}}" type="text/javascript"></script>

<script src="{{asset('theme/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>
<!--for select 2-->
<script src="{{asset('theme/global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
<!--for select 2-->


<!--for date-->
<script src="{{ asset("theme/global/plugins/moment.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("theme/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("theme/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("theme/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("theme/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("theme/global/plugins/clockface/js/clockface.js") }}" type="text/javascript"></script>
<!--for date-->

<script src="{{asset('theme/global/plugins/ladda/spin.min.js')}}" type="text/javascript"></script>
<script src="{{asset('theme/global/plugins/ladda/ladda.min.js')}}" type="text/javascript"></script>
{{-- <script src="{{asset('theme/js/autosize.js')}}" type="text/javascript"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script src="{{asset('theme/js/custom.js')}}" type="text/javascript"></script>
<script>
     Ladda.bind( '.ladda-button', { timeout: 2000 } );
    //Ladda.bind('input[type=submit]', { timeout: 2000 } );
</script>
@include('admin.layout.notification')
@yield('script')

    <script>
        $(document).ready(function() {
            $('.toggler-class').on('click', function(e) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('admin/user/toggle_collapse') }}"
                });
            });
        });
    </script>

</body>

</html>