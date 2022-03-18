@extends('layouts.uking')

@section('css')
    
@endsection

@section('content')

<input type="hidden" class="form-control" name="brand_id" id="brand_id" value="{{$brand->id}}">

    
<div class="main-container" style="margin-bottom: 100px"> 
    <h5 class="pl-3 pt-1"> <i class="fa fa-hand-holding-usd "></i> เติมเงิน</h5>
    <hr>
    <div class="container mb-2">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h6 class="subtitle mb-0">เลือกโปรโมชั่น</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                        <input type="radio" name="promotion_id" id="promotion1" value="0" checked onchange="updatePromotion({{$customer->id}},0,'ไม่รับโบนัส', 0)">
                        </div>
                    </div>
                    <input type="text" class="form-control" aria-label="Text input with radio" value="ไม่รับโบนัส" disabled>
                </div>
                @foreach($promotions->where('status','=',1) as $promotion)
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <input type="radio" name="promotion_id" id="promotion_{{$promotion->id}}" value="{{$promotion->id}}" onchange="updatePromotion({{$customer->id}},{{$promotion->id}},'{{$promotion->name}}',{{$promotion->min}})">
                            </div>
                        </div>
                        <input type="text" class="form-control" aria-label="Text input with radio" value="{{$promotion->name}}" disabled>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container mb-2">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h6 class="subtitle mb-0">โอนเงินเข้าบัญชีนี้ แล้ว เครดิตจะเข้าอัตโนมัติ</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="swiper-container swipercards">
                    <div class="swiper-wrapper pb-4">
                        @foreach($bank_accounts as $bank_account)
                        <div class="swiper-slide">
                            <div class="card border-0 bg-default text-white" style="background-color: {{$bank_account->bank->bg_color}};color: {{$bank_account->bank->font_color}};border-radius: 5px;">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-auto">
                                            <img src="{{asset($bank_account->bank->logo)}}" alt="">
                                        </div>
                                        <div class="col pl-0">
                                            <h6 class="mb-1">{{$bank_account->bank->name}}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="mb-0 mt-3">{{$bank_account->account}}</h5>
                                    <p class="small">เลขที่บัญชี</p>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col">
                                            <button type="button" class="btn btn-default btn-sm mb-2 mx-auto rounded"  data-clipboard-text="{{$bank_account->account}}" onclick="alert('{{$bank_account->account}}')">
                                                <i class="fa fa-copy"></i>
                                                คัดลอก</button>
                                            <!-- <p class="mb-0">26/21</p> -->
                                            <!-- <p class="small ">Expiry date</p> -->
                                        </div>
                                        <div class="col-auto align-self-center text-right">
                                            <p class="mb-0">{{$bank_account->name}}</p>
                                            <p class="small">ชื่อบัญชี</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="pull-right">
                </div>
            </div>
        </div>
    </div>
    <div class="container mb-2">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h6 class="subtitle mb-0">โอนจากบัญชีที่ท่านสมัครมาบัญชีนี้เท่านั้น</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="card border-0 bg-default text-white" style="background-color: {{$customer->bank->bg_color}};color: {{$customer->bank->font_color}};border-radius: 5px;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-auto">
                                <img src="{{asset($customer->bank->logo)}}" alt="">
                            </div>
                            <div class="col pl-0">
                                <h6 class="mb-1">{{$customer->bank->name}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-0 mt-3">{{$customer->bank_account}}</h5>
                        <p class="small">เลขที่บัญชี</p>
                        <div class="col-auto align-self-center text-right">
                            <p class="mb-0">{{$customer->name}}</p>
                            <p class="small">ชื่อบัญชี</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mb-2">
        <div class="card">
            <div class="card-body">
                <h5 class="text-dark"><i class="fa fa-file-alt pr-2"></i> โปรดอ่านก่อน</h5>
                <hr>
                <p class="text-dark">1. หากบัญชีธนาคารที่ท่านสมัครไม่ตรงกับบัญชีที่ใช้ฝากเงินของท่านอาจจะไม่เข้่าสู่ระบบอย่างถาวร</p>
                <p class="text-dark">2. หากท่านตรวจสอบแล้วข้อมูลไม่ถูกต้อง โปรดแจ้งทีมงานให้ทราบก่อนทำรายการ</p>
            </div>  
        </div>
    </div>
</div>
    
@endsection

@section('javascript')

<script>

    $(function() {
        new ClipboardJS('.btn');
    });
    
    function updatePromotion(customer_id,promotion_id,promotion_name,promotion_min) {

        var brand_id = $('#brand_id').val();

        var subdomain = $('#subdomain').val();

        $.post('{{route('uking.update-promotion')}}', {customer_id: customer_id,promotion_id: promotion_id, brand_id: brand_id}, function(r) {
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
    
@endsection