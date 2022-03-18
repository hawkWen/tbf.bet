@extends('layouts.uking')

@section('css')
    
@endsection

@section('content')

<input type="hidden" class="form-control" name="brand_id" id="brand_id" value="{{$brand->id}}">

<form action="{{route('uking.member.deposit.fast.store', $brand->subdomain)}}" method="post" enctype="multipart/form-data" id="formDepositManual">
    <input type="hidden" name="line_user_id" value="{{$customer->line_user_id}}">
    <div class="main-container" style="margin-bottom: 100px"> 
        <h5 class="pl-3 pt-1"> <i class="fa fa-hand-holding-usd "></i> เติมเงิน</h5>
        <hr>
        <div class="container mb-2">
            <div class="alert alert-warning" role="alert">
                <small>เนื่องจากระบบฝากอัตโนมัติมีปัญหา จึงจำเป็นต้องให้ลูกค้าแนปสลิป เป็นการชั่วคราว ขออภัยในความไม่สะดวกค่ะ</small>
            </div>
        </div>
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
            <div class="swiper-container swipercards">
                <div class="swiper-wrapper pb-4">
                    <div class="swiper-slide">
                        <div class="card border-0 bg-default text-white" style="background-color: {{$bank_account_reserve->bank->bg_color}};color: {{$bank_account_reserve->bank->font_color}};border-radius: 5px;">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-auto">
                                        <img src="{{asset($bank_account_reserve->bank->logo)}}" alt="">
                                    </div>
                                    <div class="col pl-0">
                                        <h6 class="mb-1">{{$bank_account_reserve->bank->name}}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-0 mt-3">{{$bank_account_reserve->account}}</h5>
                                <p class="small">เลขที่บัญชี</p>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-default btn-sm mb-2 mx-auto rounded"  data-clipboard-text="{{$bank_account_reserve->account}}" onclick="alert('{{$bank_account_reserve->account}}')">
                                            <i class="fa fa-copy"></i>
                                            คัดลอก</button>
                                        <!-- <p class="mb-0">26/21</p> -->
                                        <!-- <p class="small ">Expiry date</p> -->
                                    </div>
                                    <div class="col-auto align-self-center text-right">
                                        <p class="mb-0">{{$bank_account_reserve->name}}</p>
                                        <p class="small">ชื่อบัญชี</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mb-2">
            <div class="form-check">
                <input type="hidden" name="bank_account_id" id="bank_account_id" value="{{$bank_account_reserve->id}}">
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <label for="">จำนวนเงินที่เติม</label>
                    <input type="number" class="form-control form-white" name="amount" placeholder="0" input-type="money_decimal">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <label for="">สลิป</label>
                    <input type="file" class="form-control form-white" name="slip" input-type="money_decimal">
                </div>
            </div>
            <div class="pull-right mt-3">
                <button type="submit" class="btn btn-success btn-sm mb-2 mx-auto rounded">
                    <i class="fa fa-check"></i>
                    เติมเงิน</button>
            </div>
        </div>
        <br>
        <br>
        <div class="container mb-2 mt-2">

            
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">คำเตือน !</h4>
                <p>กรุณาใช้บัญชีธนาคารทีสมัคร ในการโอนเงิน กับเราเท่านั้น</p>
                <hr>
                <small>หากต้องการเปลี่ยนบัญชีธนาคารกรุณาติดต่อเจ้าหน้าที่ ตลอด 24 ชั่วโมง</small>
            </div>
        </div>
    </div>
</form>

{!! JsValidator::formRequest('App\Http\Requests\MemberDepositRequest','#formDepositManual') !!}
    
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