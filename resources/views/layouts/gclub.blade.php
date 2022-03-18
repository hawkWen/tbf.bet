<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{strtoupper($brand->name)}}</title>

    @include('parts.gclub.css')

    @yield('css')

</head>
<body style="padding-bottom: 200px;">

    <div class="container">
        <img src="{{$brand->logo_url}}" class="img-fluid img-center mt-5 mb-5" width="150" alt="">
    </div>

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
                    <p class="alert alert-{{ $msg }} mb-0">
                        {{ Session::get('alert-' . $msg) }}
                    </p>
                @endif
            @endforeach
        </div>
    </div>
    @if(Auth::guard('customer')->check() && !Request::is($brand->name.'/member/promotion'))
        @include('parts.gclub.header')
    @endif
    
    @yield('content')

    @include('parts.gclub.javascript')

    @yield('javascript')

    @if(Auth::guard('customer')->check() && !Request::is($brand->name.'/member/promotion'))
        @include('parts.gclub.menu')
    @endif

</body>
</html>