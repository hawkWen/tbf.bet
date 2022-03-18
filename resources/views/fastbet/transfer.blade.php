@extends('layouts.fastbet89-auth')

@section('css')
        
@endsection

@section('content')

<input type="hidden" class="form-control" name="brand_id" id="brand_id" value="{{$brand->id}}">

<input type="hidden" class="form-control" name="subdomain" id="subdomain" value="{{$brand->subdomain}}">

<input type="hidden" class="form-control" name="line_liff_transfer" id="line_liff_transfer" value="{{$brand->line_liff_transfer}}">

<div id="content" style="display: none">
</div>
    
@endsection

@section('javascript')

@if(env('APP_ENV') == 'local')

<script>
    $(function() {
        $('#content').load('transfer/view/Uf0509252c3ab7c915d4d4626ffaab805', function(r) {
            $('#loading').fadeOut();
            $('#content').fadeIn();
            renderInput();
        });
        new ClipboardJS('.btn');
        $(document).keypress(
        function(event){
            if (event.which == '13') {
            event.preventDefault();
            }
        });
    });
    function updatePromotion(customer_id,promotion_id,promotion_name,promotion_min) {

        var brand_id = $('#brand_id').val();

        var subdomain = $('#subdomain').val();

        $.post('{{route('fastbet.update-promotion')}}', {customer_id: customer_id,promotion_id: promotion_id, brand_id: brand_id}, function(r) {
            $('#alertPromotion').fadeIn();
            if(r.status == true) {
                $.notify(promotion_name + ' โอนเงินขั้นต่ำ ' + promotion_min,'info');
                setTimeout(function() {
                    $('#alertPromotion').fadeOut();
                },3000);
            }
        });

    }
</script>

@else

<script>

    $(function() {
        new ClipboardJS('.btn');
    });

    function runApp() {
        liff.getProfile().then(profile => {
            checkAuth(profile);
        }).catch(err => console.error(err));
    }
    liff.init({ liffId: $('#line_liff_transfer').val() }, () => {
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

        $.post('{{route('fastbet.check-auth')}}',{line_user_id: profile.userId, brand_id: brand_id}, function(r) {
            
            if(r.code == 200) {
                
                $('#content').load('transfer/view/' + profile.userId, function(r) {
                    $('.loader-display').fadeOut('slow');
                    $('#content').show();
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

        $.post('{{route('fastbet.update-promotion')}}', {customer_id: customer_id,promotion_id: promotion_id, brand_id: brand_id}, function(r) {
            $('#alertPromotion').fadeIn();
            if(r.status == true) {
                $.notify(promotion_name + ' โอนเงินขั้นต่ำ ' + promotion_min,'info');
                setTimeout(function() {
                    $('#alertPromotion').fadeOut();
                },3000);
            }
        });

    }

</script>

<script>

    // jQuery plugin to prevent double submission of forms
    jQuery.fn.preventDoubleSubmission = function() {
        $(this).on('submit',function(e){
            var $form = $(this);

            if ($form.data('submitted') === true) {
            // Previously submitted - don't submit again
            e.preventDefault();
            } else {
            // Mark it so that the next submit can be ignored
            $form.data('submitted', true);
            }
        });

        // Keep chainability
        return this;
    };

    $(function() {
        $('#formWithdrawManual').preventDoubleSubmission();
    });

</script>

@endif

@endsection