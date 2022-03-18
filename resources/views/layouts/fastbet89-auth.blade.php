<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Fastbet89</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <!-- <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png"> -->

    @include('parts.fastbet89.css')

    @yield('css')

</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">

    @include('parts.fastbet89.header')

    <div class="container">
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
                    <p class="alert alert-{{ $msg }} mb-2">
                        {{ Session::get('alert-' . $msg) }}
                    </p>
                @endif
            @endforeach
        </div>
    </div>

            @yield('content')


    @include('parts.fastbet89.javascript')

    @yield('javascript')

</body>

</html>
