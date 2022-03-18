@extends('layouts.frontend3')

@section('css')
    
@endsection

@section('content')
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="pb-3 pr-2">
        <div class="">
            <h2 class="float-left mb-0 text-white">{{$brand->name}}</h2>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="content-body">
        <hr>
        <!-- Kick start -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-warning">
                    <div class="card-body">
                        <h5 class="text-dark"><i class="fa fa-file-alt pr-2"></i> โปรดอ่านก่อน</h5>
                        <hr class="hr-red">
                        <p class="text-dark">1. หากบัญชีธนาคารที่ท่านสมัครไม่ตรงกับบัญชีที่ใช้ฝากเงินของท่านอาจจะไม่เข้่าสู่ระบบอย่างถาวร</p>
                        <p class="text-dark">2. หากท่านตรวจสอบแล้วข้อมูลไม่ถูกต้อง โปรดแจ้งทีมงานให้ทราบก่อนทำรายการ</p>

                        <p class="text-dark mt-2">
                            บัญชีของคุณ
                        </p>
                        <p class="text-dark">
                            <img src="{{asset($customer->bank->logo)}}" alt="" width="30"> {{$customer->name}} {{$customer->bank_account}}
                        </p>
                    </div>  
                </div>
            </div>
            <div class="col-lg-12 col-md-8">
                <h4>1. เลือกโปรโมชั่นก่อนโอนเงิน</h4>
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
        <h4 class="mt-2">2. โอนเงินเข้าบัญชีธนาคารของเรา แล้ว เครดิตจะเข้าอัตโนมัติ</h4>
        <div class="row">
            @foreach($bank_accounts as $bank_account)
                <div class="col-lg-4 col-6">
                    <div class="card" style="background-color: {{$bank_account->bank->bg_color}};color: {{$bank_account->bank->font_color}} !important;border-radius: 5px;">
                        <div class="card-body">
                            <img src="{{asset($bank_account->bank->logo)}}" class="img-fluid img-center" width="50" alt="">
                            <h6 class="mt-1" style="color: {{$bank_account->bank->font_color}} !important;">{{$bank_account->bank->name}}</h6>
                            <h4 class="mt-1" style="color: {{$bank_account->bank->font_color}} !important;">{{$bank_account->account}}</h4>
                            <p class="mb-0">{{$bank_account->name}}</p>
                            <button type="button" class="btn btn-info btn-sm mt-2 mx-auto rounded text-dark"  data-clipboard-text="{{$bank_account->account}}" onclick="alert('{{$bank_account->account}}')">
                                <i class="fa fa-copy"></i>
                            คัดลอก</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<input type="hidden" class="form-control" name="brand_id" id="brand_id" value="{{$brand->id}}">
    
@endsection

@section('javascript')

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

                // $.notify(promotion_name,'info');
                toastr['success'](promotion_name, 'สำเร็จ', {
                    closeButton: true,
                    tapToDismiss: false,
                    timeOut: 3000,
                });
            }
        });

    }
    
</script>
    
@endsection