@extends('layouts.uking-auth')

@section('css')

<style>
    
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
        color: white;
    }

</style>
    
@endsection

@section('content')


<input type="hidden" class="form-control" name="brand_id" id="brand_id" value="{{$brand->id}}">

<input type="hidden" class="form-control" name="subdomain" id="subdomain" value="{{$brand->subdomain}}">

<input type="hidden" class="form-control" name="line_liff_connect" id="line_liff_connect" value="{{$brand->line_liff_connect}}">

<input type="hidden" class="form-control" name="line_liff_info" id="line_liff_info" value="{{$brand->line_liff_info}}">

<div class="container">
    <div id="content" style="display: block">
    </div>

</div>
    
@endsection

@section('javascript')

@if(env('APP_ENV') == 'local')
<script>
    $(function() {
        $('#content').load('info/view/U66b2f22a6a13de14792bdbd4276ecea1', function(r) {
            $('#loading').fadeOut();
            $('#content').fadeIn();
            renderInput();
        });
    });
</script>

@else

<script>

    var line_liff_info = $('#line_liff_info').val();

    $(function() {
        new ClipboardJS('.btn');
    });

    function runApp() {
        liff.getProfile().then(profile => {
            console.log(profile);
            checkAuth(profile);
        }).catch(err => console.error(err));
    }
    liff.init({ liffId: line_liff_info }, () => {
        if (liff.isLoggedIn()) {
            runApp();
        } else {
            liff.login();
        }
    }, err => console.error(err));

    function checkAuth(profile) {

        $('#loading').fadeIn();

        var brand_id = $('#brand_id').val();

        var subdomain = $('#subdomain').val();

        $('.loader-display').fadeIn();

        $.post('{{route('uking.check-auth')}}',{line_user_id: profile.userId, brand_id: brand_id}, function(r) {
            
            if(r.code == 200) {
                
                $('#content').load('info/view/' + profile.userId, function(r) {
                    $('#content').show();
                    $('.loader-display').fadeOut('slow');
                    renderInput();
                });

            } else {

                window.location.href = '/' + subdomain + '/register';

            }
        });

    }

</script>

@endif

@endsection