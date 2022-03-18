<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connect</title>
</head>
<body>

<input type="hidden" class="form-control" name="brand_id" id="brand_id" value="{{$brand->id}}">
<input type="hidden" class="form-control" name="brand_suddomain" id="brand_suddomain" value="{{$brand->subdomain}}">
<input type="hidden" class="form-control" name="line_liff_connect" id="line_liff_connect" value="{{$brand->line_liff_connect}}">
<input type="hidden" class="form-control" name="username" id="customerUsername" value="{{$customer->username}}">
<script src="{{asset('libs/jquery.min.js')}}"></script>
<script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
  
<script>

    var pathname = window.location.pathname;

    var line_liff_connect = $('#line_liff_connect').val();

    liff.init({ liffId: line_liff_connect }, () => {
        if (liff.isLoggedIn()) {
            runApp();
        } else {
            liff.login();
        }
    }, err => {console.log(err)});

    function runApp() {
        var username = $('#customerUsername').val();
        var brand_id = $('#brand_id').val();
        var subdomain = $('#brand_suddomain').val();
        liff.getProfile().then(profile => {
            console.log(profile);
            console.log(username); 
            $.post('/' + subdomain + '/member/connect/store', {username: username, line_user_id: profile.userId, picture: profile.pictureUrl, brand_id: brand_id}, function() {
                alert('เชื่อมต่อกับบัญชี LINE เรียบร้อยขอบคุณค่ะ');
                window.location.href = '/' + subdomain + '/member'
            });
        }).catch(err => {console.log(err)});
    }

</script>
</body>
</html>