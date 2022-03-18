@php 

if(env('APP_ENV') == 'local') {

    $sub_domain = (explode('/', $_SERVER['REQUEST_URI']))[1];

    $game = (explode('.', $_SERVER['SERVER_NAME']))[0];

} else {

    $sub_domain = (explode('/', $_SERVER['REQUEST_URI']))[1];

    $game = (explode('.', $_SERVER['HTTP_HOST']))[0];

}

$brand = App\Models\Brand::whereSubdomain($sub_domain)->first();

@endphp
<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">
    <!-- BEGIN: Head-->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
        <meta name="description" content="{{$brand->name}} ระบบอัตโนมัติ คาสิโนออนไลน์ slot royal gclub fastbet89" />
        <meta name="keywords" content="{{$brand->name}} ระบบอัตโนมัติ คาสิโนออนไลน์ slot royal gclub fastbet89" />
        <meta name="author" content="Casinoauto.io" />
        <title>{{strtoupper($brand->name)}}</title>
        <!-- <link rel="apple-touch-icon" href="app-assets/images/ico/apple-icon-120.png" />
        <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.ico" /> -->
        @include('parts.frontend3.css')
        @yield('css')
    </head>
    <body class="vertical-layout vertical-menu-modern navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="">
        <!-- BEGIN: Main Menu-->
        @include('parts.frontend3.header')

        <div class="app-content content">
            <div class="flash-message">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <div class="alert alert-{{ $msg }} mb-2">
                            <p class="mb-0">
                                {{ Session::get('alert-' . $msg) }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
            
            @yield('content')

            @include('parts.frontend3.menu')

        </div>

        @include('parts.frontend3.footer')
        
        @include('parts.frontend3.javascript')

        @yield('javascript')
        <form id="logout-form" action="{{ route($brand->game->name.'.member.logout', $brand->subdomain) }}" method="POST" style="display: none;">
            @csrf
        </form>
    </body>
    <!-- END: Body-->
</html>
