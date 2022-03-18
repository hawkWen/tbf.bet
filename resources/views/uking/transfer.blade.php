@extends('layouts.uking-auth')

@section('css')
        
@endsection

@section('content')

<input type="hidden" class="form-control" name="brand_id" id="brand_id" value="{{$brand->id}}">

<input type="hidden" class="form-control" name="subdomain" id="subdomain" value="{{$brand->subdomain}}">

<input type="hidden" class="form-control" name="line_liff_transfer" id="line_liff_transfer" value="{{$brand->line_liff_transfer}}">

<div class="container">
    
    <div id="content" style="display: none">
    </div>

</div>
    
@endsection

@section('javascript')
@if(env('APP_ENV') == 'local')
<script>
    $(function() {
        $('#content').load('transfer/view/U79eeab1dbe0c42be1cecb62db88e4161', function(r) {
            $('#loading').fadeOut();
            $('#content').fadeIn();
            renderInput();
        });
    });
</script>

@else

<script>

    var line_liff_transfer = $('#line_liff_transfer').val();

    $(function() {
        new ClipboardJS('.btn');
    });

    function runApp() {
        liff.getProfile().then(profile => {
            checkAuth(profile);
        }).catch(err => console.error(err));
    }
    liff.init({ liffId: line_liff_transfer }, () => {
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
                
                $('#content').load('transfer/view/' + profile.userId, function(r) {
                    $('#content').show();
                    $('.loader-display').fadeOut('slow');
                    renderInput();
                    $(document).keypress(
                    function(event){
                        if (event.which == '13') {
                            event.preventDefault();
                        }
                    });
                });

            } else {

                window.location.href = '/' + subdomain + '/register';

            }
        });

    }

    function updatePromotion(customer_id,promotion_id,promotion_name,promotion_min) {

        var brand_id = $('#brand_id').val();

        var subdomain = $('#subdomain').val();

        $.post('{{route('uking.update-promotion')}}', {customer_id: customer_id,promotion_id: promotion_id, brand_id: brand_id}, function(r) {
            $('#alertPromotion').fadeIn();
            if(r.status == true) {
                $.notify(promotion_name + ' โอนเงินขั้นต่ำ ' + promotion_min,'info');
                // $('#promotionName').html(promotion_name);
                // $('#bonusMin').html(promotion_min);
                setTimeout(function() {
                    $('#alertPromotion').fadeOut();
                },3000);
            }
        });

    }

</script>

@endif

@endsection