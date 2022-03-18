
<ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
    <li class="nav-item">
        <button class="btn btn-default btn-block active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true" style="border-radius: 0px;">
            <i class="fa fa-user"></i>
            ข้อมูลลูกค้า</button>
    </li>
    <li class="nav-item">
        <button class="btn btn-default btn-block" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false" style="border-radius: 0px;">
        <i class="fa fa-file-alt"></i>
        ประวัติการทำรายการ</button>
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
            <div class="main-container" style="margin-bottom: 100px"> 
                <h5 class="pl-3 pt-1"> <i class="fa fa-user"></i> ข้อมูลลูกค้า</h5>
                <hr> 
                @if($customer->username != '' || $customer->password != '')
                    @php
                        $deposit_last = $customer->deposits->where('status','=',1)->sortByDesc('created_at')->first()
                    @endphp
                <div class="container mb-2">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <p>ไอดีเข้าเล่นเกมส์</p>
                                    <div class="">
                                        <p class=" text-dark text-shadow mt-4">
                                            <b>Username:</b> {{$customer->username}}
                                            <button href="javascript:void(0);" class="btn pull-right pb-2" data-clipboard-text="{{$customer->username}}" onclick="alert('{{$customer->username}}')" style="border: none;background-color: transparent;">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </p>
                                        <hr>
                                        <p class=" text-dark text-shadow">
                                            <b>Password:</b> {{$customer->password_generate}}
                                            <button href="javascript:void(0);" class="btn pull-right pb-2" data-clipboard-text="{{$customer->password_generate}}" onclick="alert('{{$customer->password_generate}}')" style="border: none;background-color: transparent;">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="container mb-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <div class="alert alert-warning" role="alert">
                                        <h4 class="alert-heading">คำแนะนำ</h4>
                                        <p>เติมเงินครั้งแรก เพื่อรับ ไอดี และ รหัสผ่านเข้าเกมส์</p>
                                        <hr>
                                        <small>หากเครดิตไม่เข้ากรุณาติดต่อเจ้าหน้าที่ ตลอด 24 ชั่วโมง</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="container mb-2">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <p><b>ชื่อ</b> : {{$customer->name}}</p>
                                    <p><b>เบอร์โทรศัพท์</b> : {{$customer->telephone}}</p>
                                    <p><b>ไลน์ไอดี</b> : {{$customer->line_id}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mb-2">
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">คำเตือน !</h4>
                        <p class="">กรุณาใช้บัญชีธนาคารด้านล่างในการโอนเงิน มาเล่นเกมส์ กับเราเท่านั้น</p>
                        <hr>
                        <small>หากต้องการเปลี่ยนบัญชีธนาคารกรุณาติดต่อเจ้าหน้าที่ ตลอด 24 ชั่วโมง</small>
                    </div>
                </div>
            </div>
        </div>  
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="main-container">
                <h5 class="pl-3 pt-1"> <i class="fa fa-file-alt"></i> ประวัติการทำรายการ</h5>
                <hr>
                <div class="container mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="list-group list-group-flush">
                                        @foreach($histories->sortByDesc('created_at') as $history)
                                            <li class="list-group-item p-2">
                                                <div class="row align-items-center">
                                                    <div class="col-auto pr-0">
                                                        @if(isset($history->status_credit))
                                                            <span class="text-danger">
                                                                <i class="fa fa-credit-card"></i>
                                                            </span>
                                                        @else
                                                            <span class="text-success">
                                                                <i class="fa fa-hand-holding-usd"></i>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="col align-self-center pr-0">
                                                        <h6 class="small text-secondary cut-text">
                                                            
                                                            @if($history->promotion)
                                                                {{$history->promotion->name}}
                                                            @else
                                                                
                                                                @if(isset($history->status_credit))
                                                                    ถอนเงิน
                                                                @else
                                                                    ไม่รับโบนัส
                                                                @endif
                                                            @endif
                                                        </h6>
                                                        <p class="small text-secondary">
                                                            {{$history->created_at->format('d/m/y H:i:s')}}
                                                        </p>
                                                    </div>
                                                    <div class="col-auto">
                                                        @if(isset($history->status_credit))
                                                            <h6 class="text-danger">
                                                                {{$history->amount}}
                                                            </h6>
                                                        @else
                                                            <h6 class="text-success">
                                                                {{$history->amount}}
                                                                @if($history->promotion)
                                                                    + {{$history->bonus}}
                                                                @endif
                                                            </h6>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <br>
</main>