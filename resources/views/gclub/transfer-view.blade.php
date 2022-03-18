<ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
    <li class="nav-item">
        <button class="btn btn-default btn-block active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true" style="border-radius: 0px;">
            <i class="fa fa-hand-holding-usd mr-2"></i>
            เติมเงิน</button>
    </li>
    <li class="nav-item">
        <button class="btn btn-default btn-block" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false" style="border-radius: 0px;">
        <i class="fa fa-credit-card mr-2"></i>
        ถอนเงิน</button>
    </li>
</ul>

<main class="flex-shrink-0 pt-3 main">
    <!-- page content start -->
    <div class="container mb-4 text-center">
        <a href="#" class="text-white pull-right">
            <img src="{{$brand->logo_url}}" width="40" class="img-center img-fluid" alt="">
        </a>
        <h2 class="text-white" style="padding: 40px 0px;">$ {{$customer->credit}}</h2>
        <p class="text-white mb-4">อัพเดทเครดิตล่าสุด: {{$customer->last_update_credit}}</p>
    </div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            @if($brand->type_deposit == 1)
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
                                @foreach($bank_accounts as $bank_account)
                                    <div class="col-lg-12 col-12">
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

                                <div class="card border-0 bg-default text-white" style="background-color: {{$customer->bank->bg_color}};color: {{$customer->bank->font_color}} !important;border-radius: 5px;">
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
            @else
                <form action="{{route('gclub.transfer.deposit', $brand->subdomain)}}" method="post" enctype="multipart/form-data" id="formDepositManual">
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
            @endif
        </div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <form action="{{route('gclub.transfer.withdraw', $brand->subdomain)}}" method="post" enctype="multipart/form-data" id="formWithdrawManual">
                <input type="hidden" name="line_user_id" value="{{$customer->line_user_id}}">
                <div class="main-container" style="margin-bottom: 100px"> 
                    <h5 class="pl-3 pt-1"> <i class="fa fa-credit-card "></i> ถอนเงิน</h5>
                    <hr> 
                    <div class="container mb-2">
                        <small>กดถอนเงินแล้วระบบจะโอนไปยังบัญชีของท่านโดยอัตโมนัติ</small>
                        <div class="card">
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
                            <div class="card-header">
                                <div class="row">
                                    <div class="col">
                                        @if($customer->credit < $brand->withdraw_min)
                                        <div class="pull-right">
                                            <p class="text-danger">{{$brand->withdraw_min}}0</p>
                                        </div>
                                        @endif
                                        <h6 class="subtitle mb-0">จำนวนเงินที่ต้องการถอน</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="text" class="form-control dark" name="amount" input-type="money_decimal" class="form-control" placeholder="{{$brand->withdraw_min}}">
                                @if($customer->username != '')
                                <div class="pull-right mt-3">
                                    <button type="submit" class="btn btn-default btn-sm mb-2 mx-auto rounded">
                                        <i class="fa fa-check"></i>
                                        ถอนเงิน</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

{!! JsValidator::formRequest('App\Http\Requests\MemberDepositRequest','#formDepositManual') !!}

{!! JsValidator::formRequest('App\Http\Requests\MemberWithdrawRequest','#formWithdrawManual') !!}
