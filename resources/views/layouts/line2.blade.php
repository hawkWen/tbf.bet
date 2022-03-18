@php

    if(env('APP_ENV') == 'local') {

        $sub_domain = (explode('.', $_SERVER['SERVER_NAME']))[0];

    } else {

        $sub_domain = (explode('.', $_SERVER['HTTP_HOST']))[0];

    }

    $brand = App\Models\Brand::whereSubdomain($sub_domain)->first();

@endphp
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    
    @include('parts.line.css2')

    @yield('css')

</head>
<body>
    
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger mt-2">
                <ul class="mb-0 mt-0 pl-0">
                    @foreach ($errors->all() as $error)
                        <li style="list-style-type: none">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <p class="alert alert-{{ $msg }} mb-0">
                        {{ Session::get('alert-' . $msg) }}
                    </p>
                @endif
            @endforeach
        </div>
    </div>
    
    @yield('content')

    @include('parts.line.javascript')

    @yield('javascript')

</body>
</html>