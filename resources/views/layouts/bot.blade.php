<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->
    <head>
        <meta charset="utf-8" />
        <title>{{env('APP_NAME')}}</title>
        <meta name="description" content="ระบบเอเย่นต์คาสิโน" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('libs/font-awesome5/css/fontawesome-all.min.css')}}">
        <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('libs/select2/css/select2.min.css')}}">

        <style>

            .img-center {
                display: block;
                margin: 0 auto;
            }

            .pull-right {
                float: right !important;
            }

        </style>

        @yield('css')

    </head>
    <!--end::Head-->

    <!--begin::Body-->
    <body>
        
        @yield('content')
        
    </body>

    <script src="{{asset('libs/jquery.min.js')}}"></script>
    <script src="{{asset('bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('libs/select2/js/select2.full.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>


    @yield('javascript')


</html>
