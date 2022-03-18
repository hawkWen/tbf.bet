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
    <title>{{strtoupper($brand->name)}}</title>

    @include('parts.ufabet.css')

    @yield('css')

</head>
<body>

    <div class="container">
        <img src="{{$brand->logo_url}}" class="img-fluid img-center mt-2 mb-2" width="150" alt="">
    </div>

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

    @if(Auth::guard('customer')->check() && !Request::is($brand->name.'/member/promotion'))
        @include('parts.ufabet.header')
    @endif
    
    @yield('content')

    @include('parts.ufabet.javascript')

    @yield('javascript')

    @if(Auth::guard('customer')->check() && !Request::is($brand->name.'/member/promotion'))
        @include('parts.ufabet.menu')
        <form method="post" action="https://www.ufabet.com/Default8.aspx?lang=EN-GB" id="form1">
            <div class="aspNetHidden">
                <input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
                <input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
                <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUKLTc2NjMxOTE5Nw9kFgJmD2QWBgICDxYCHgtwbGFjZWhvbGRlcgUe4LiK4Li34LmI4Lit4Lic4Li54LmJ4LmD4LiK4LmJZAIDDxYCHwAFGOC4o+C4q+C4seC4quC4nOC5iOC4suC4mWQCBA8PFgIeBFRleHQFIeC5gOC4guC5ieC4suC4quC4ueC5iOC4o+C4sOC4muC4mmRkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYBBQlidG5Mb2dpbjK0GvGzmtq4/Y17MirZEaYkr5WZJg==" />
            </div>
            <input name="txtUserName" type="hidden" id="txtUserName" tabindex="1" style="width:120px;" class="UsernameCss" value="{{Auth::guard('customer')->user()->username}}" maxlength="16" placeholder="ชื่อผู้ใช้" />
            <input name="password" type="hidden" id="password" tabindex="3" style="width:120px;" class="PasswordCss" value="{{Auth::guard('customer')->user()->password_generate}}" maxlength="16" placeholder="รหัสผ่าน" />
            <script type="text/javascript">
                //<![CDATA[
                var theForm = document.forms['form1'];
                if (!theForm) {
                    theForm = document.form1;
                }
                function __doPostBack(eventTarget, eventArgument) {
                    if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
                        theForm.__EVENTTARGET.value = eventTarget;
                        theForm.__EVENTARGUMENT.value = eventArgument;
                        theForm.submit();
                    }
                }
            //]]>
        </script>
    @endif

</body>
</html>