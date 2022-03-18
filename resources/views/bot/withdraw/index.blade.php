@extends('layouts.bot')

@section('css')

@endsection

@section('content')

<input type="hidden" name="brand_id" id="brandId" value="{{$brand->id}}">

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title"></div>
             </div>
            <div class="panel-body" id="botList">
                <div class="container">
                    <img src="{{$brand->logo_url}}" class="img-fluid img-center mt-5 mb-5" width="150" alt="">
                </div>            
                <h1 class="text-center">บอทถอนเงินกำลังทำงาน ...</h1>
             </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')

<script>

    var status_bot_deposit = false;
        
        var refresh_times = 1800;
    
    $(function() {
        i = 0;
        runBot();
        setInterval(function() {
            if(status_bot_deposit === true) {

                runBot();

            }
            i++;
        },10000);
        setInterval(function() {
            refresh_times--;
            console.log(refresh_times);
            if(refresh_times <= 0) {
                if(status_bot_deposit === true) {

                    location.reload(true);

                }
            }
        },1500);
    });

    function runBot() {

        status_bot_deposit = false;

        var brand_id = $('#brandId').val();

        $.post('{{route('bot.withdraw.otp.store')}}',{brand_id: brand_id}, function() {
            status_bot_deposit = true;
        }).fail(function() {
            status_bot_deposit = true;
        });

    }

</script>

@endsection