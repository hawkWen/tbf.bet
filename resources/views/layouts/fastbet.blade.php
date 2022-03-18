<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{strtoupper($brand->name)}}</title>

    @include('parts.fastbet.css')

    @yield('css')

</head>
<body style="padding-bottom: 150px;">
    <div class="row">
        <div class="col-lg-8 mx-auto">
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
                            <p class="alert alert-{{ $msg }} mb-2">
                                {{ Session::get('alert-' . $msg) }}
                            </p>
                        @endif
                    @endforeach
                </div>
            </div>

            @if(Auth::guard('customer')->check() && !Request::is($brand->name.'/member/promotion'))
                @include('parts.fastbet.header')
            @endif
            
            @yield('content')
        
            @include('parts.fastbet.javascript')
        
            @yield('javascript')
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mx-auto">

            @if(Auth::guard('customer')->check() && !Request::is($brand->name.'/member/promotion'))
                @include('parts.fastbet.menu')
            @endif

        </div>
    </div>

</body>
</html>