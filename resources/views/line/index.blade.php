<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LINE CONNECT</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/line.css') }}">
</head>

<body>
    <section class="section">
        <img src="{{ asset('images/line-notify.png') }}" class="img-fluid" style="
        width: 120px !important;
        height: 120px !important;" />
        <div class="content">

            <h2 class="title text-white">
                LINE CONNECT
            </h2>
            <small class="paragraph text-white">
                เชื่อมต่อบัญชี LINE เพื่อรับการแจ้งเตือนและสิทธิพิเศษอีกมากมาย
            </small>
        </div>
    </section>
    <section class="content">
        <div class="body">

            <img src="{{ asset('images/line-notify-2.jpeg') }}" class="img-center" alt="">
            <label for="">ระบุไอดีเข้าเกมส์</label>
            <input type="hidden" class="form-control" name="brand_id" id="brand_id" value="{{ $brand->id }}">
            <input type="hidden" class="form-control" name="brand_suddomain" id="brand_suddomain"
                value="{{ $brand->subdomain }}">
            <input type="hidden" class="form-control" name="line_liff_connect" id="line_liff_connect"
                value="{{ $brand->line_liff_connect }}">
            <input type="text" class="form-control" name="username" id="username"
                placeholder="{{ $brand->agent_prefix }}">
            <br>
            <button type="button" class="btn btn-success btn-block btn-lg" onclick="connect()">เชื่อมต่อบัญชี</button>
        </div>
    </section>
    <script src="{{ asset('libs/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
    <script>
        var pathname = window.location.pathname;

        var line_liff_connect = $('#line_liff_connect').val();

        liff.init({
            liffId: line_liff_connect
        }, () => {
            if (liff.isLoggedIn()) {
                // runApp();
            } else {
                liff.login();
            }
        }, err => {});

        function connect() {
            var username = $('#username').val();
            var brand_id = $('#brand_id').val();
            var brand_subdomain = $('#brand_suddomain').val();
            liff.getProfile().then(profile => {
                console.log(profile);
                $.post('{{ route('line.store') }}', {
                    username: username,
                    line_user_id: profile.userId,
                    picture: profile.pictureUrl,
                    brand_id: brand_id
                }, function(r) {
                    console.log(r);
                    if (r.status == false) {
                        alert(r.message);
                    } else {
                        alert(r.message);
                        window.location.href = "https://casinoauto.io/" + brand_subdomain +
                            "/member";
                    }
                });
            }).catch(err => {
                console.log(err)
            });
        }

    </script>
</body>

</html>
