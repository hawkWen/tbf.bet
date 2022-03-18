@extends('layouts.frontend3')

@section('css')
    
@endsection

@section('content')
<input type="hidden" class="form-control" name="brand_id" id="brand_id" value="{{$brand->id}}">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="pb-3 pr-2">
        <div class="">
            <h2 class="float-left mb-0 text-white">{{$brand->name}}</h2>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="content-body">

        <form action="{{route('fastbet.member.deposit.fast.store', $brand->subdomain)}}" method="post" enctype="multipart/form-data" id="formDepositManual">
            <input type="hidden" name="line_user_id" value="{{$customer->line_user_id}}">
            <input type="hidden" name="customer_id" value="{{$customer->id}}">
            <div class="main-container" style="margin-bottom: 100px"> 
                <hr>
                <div class="row mb-2">
                    <div class="col-lg-12">
                        <div class="alert alert-warning" role="alert">
                            <p>เนื่องจากระบบฝากอัตโนมัติมีปัญหา จึงจำเป็นต้องให้ลูกค้าแนปสลิป เป็นการชั่วคราว ขออภัยในความไม่สะดวกค่ะ</ย>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">คำเตือน !</h4>
                            <p>กรุณาใช้บัญชีธนาคารทีสมัคร ในการโอนเงิน กับเราเท่านั้น</p>
                            <hr class="hr-red">
                            <p>หากต้องการเปลี่ยนบัญชีธนาคารกรุณาติดต่อเจ้าหน้าที่ ตลอด 24 ชั่วโมง</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-8">
                        <h4 class="mb-1">1. เลือกโปรโมชั่น</h4>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                <input type="radio" name="promotion_id" id="promotion1" value="0" checked onchange="updatePromotion({{$customer->id}},0,'ไม่รับโบนัส', 0)">
                                </div>
                            </div>
                            <input type="text" class="form-control" aria-label="Text input with radio" value="ไม่รับโบนัส" disabled>
                        </div>
                        @foreach($promotions->where('status','=',1) as $promotion)
                            <div class="input-group">
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
                <br>
                @if(isset($bank_account_reserve->bank))
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <h4>2. บัญชีธนาคารสำรอง</h4>
                            <div class="card" style="background-color: {{$bank_account_reserve->bank->bg_color}};color: {{$bank_account_reserve->bank->font_color}} !important;border-radius: 5px;">
                                <div class="card-body">
                                    <img src="{{asset($bank_account_reserve->bank->logo)}}" class="img-fluid img-center" width="50" alt="">
                                    <h6 class="mt-1" style="color: {{$bank_account_reserve->bank->font_color}} !important;">{{$bank_account_reserve->bank->name}}</h6>
                                    <h4 class="mt-1" style="color: {{$bank_account_reserve->bank->font_color}} !important;">{{$bank_account_reserve->account}}</h4>
                                    <p class="mb-0">{{$bank_account_reserve->name}}</p>
                                    <button type="button" class="btn btn-info btn-sm mt-2 mx-auto rounded text-dark"  data-clipboard-text="{{$bank_account_reserve->account}}" onclick="alert('{{$bank_account_reserve->account}}')">
                                        <i class="fa fa-copy"></i>
                                    คัดลอก</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="hidden" name="bank_account_id" id="bank_account_id" value="{{$bank_account_reserve->id}}">
                    </div>
                @endif
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
                    <button type="submit" class="btn btn-success btn-lg btn-sm mb-2 mx-auto rounded">
                        <i class="fa fa-check"></i>
                        เติมเงิน</button>
                </div>
            </div>
        </form>
    </div>
</div>
    
@endsection

@section('javascript')

{!! JsValidator::formRequest('App\Http\Requests\MemberDepositRequest','#formDepositManual') !!}

<script>

    $(function() {
        new ClipboardJS('.btn');
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
    
@endsection