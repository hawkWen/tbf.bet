<input type="hidden" id="depositCount" value="{{$customer_deposits->where('status',0)->count()}}">
<div class="row">
    <div class="col-lg-12">
        <h3>รายการธนาคารขาเข้า</h3>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-success text-white">
                    <th>บัญชีธนาคาร</th>
                    <th>ยอดเงิน</th>
                    <th>ประเภท</th>
                    <th>สถานะบอท</th>
                    <th>รายกาค้าง</th>
                </tr>
            </thead>
            <tbody>
            @foreach($bank_accounts as $bank_account)
                <tr style="background-color: {{$bank_account->bank->bg_color}};color: {{$bank_account->bank->font_color}};">
                    <td>
                        <img src="{{asset($bank_account->bank->logo)}}" width="20" class="img-fluid" alt="" width="20">
                        {{$bank_account->bank->name}} {{$bank_account->name}} {{$bank_account->account}}
                    </td>
                    <td align="center">{{$bank_account->amount}}</td>
                    <td align="center">
                        @if($bank_account->type == 1)
                            <span class="text-white">ขาเข้า (auto)</span>
                        @elseif($bank_account->type == 2)
                            <span class="text-white">ขาเข้าสำรอง (manual)</span>
                        @endif
                    </td>
                    <td align="center">
                        @if($bank_account->type == 1)
                            @if($bank_account->status_bot == 1)
                                <span class="text-success">BOT</span>
                            @else
                                <span class="text-dark">ปิดบอท</span>
                            @endif
                        @endif
                    </td>
                    <td align="center">
                        @if($bank_account->type == 1)
                            {{$bank_account->transactions->whereIn('status',[1,3,4])->count()}} รายการ
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-lg-12">
        <h3>รายการที่รอเติมเงิน 
            <span class="symbol symbol-35 symbol-light-danger">
                <span class="symbol-label font-size-h5 font-weight-bold">
                    {{$customer_deposits->where('status',0)->count()}}
                </span>
            </span>
        </h3>
        <hr>
        <div class="row">
            <div class="col-lg-12">
                @if($customer_deposits->where('status',0)->count() > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-warning text-white">
                                <th width="50">วันที่/เวลา</th>
                                <th>Username</th>
                                <th>ลูกค้า</th>
                                <th>จำนวนเงิน</th>
                                <th>ธนาคารที่โอน</th>
                                <th>สถานะ</th>
                                <th>สลิป</th>
                                <th>เติมเงิน</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer_deposits->where('status',0)->sortByDesc('created_at') as $key_customer_deposit=>$customer_deposit)
                                <tr style="background-color: {{$customer_deposit->bankAccount->bank->bg_color}};color: {{$customer_deposit->bankAccount->bank->font_color}};">
                                    <td>{{$customer_deposit->created_at->format('d/m/Y H:i:s')}}</td>
                                    <td>
                                        @if($customer_deposit->customer)
                                            {{$customer_deposit->customer->username}}
                                        @endif
                                    </td>
                                    <td>{{$customer_deposit->name}}</td>
                                    <td align="center">{{number_format($customer_deposit->amount,2)}}</td>
                                    <td>
                                        <img src="{{asset($customer_deposit->bankAccount->bank->logo)}}" width="20" class="img-fluid" alt="" width="20">
                                        {{$customer_deposit->bankAccount->bank->name}} {{$customer_deposit->bankAccount->name}}
                                    </td>
                                    <td align="center">
                                        @if ($customer_deposit->status == 0)
                                            <span style="color: {{$customer_deposit->bankAccount->bank->font_color}};">รอพนักงานเติมเงิน</span>
                                        @elseif($customer_deposit->status == 1)
                                            <span class="text-success">เติมเงินเสร็จแล้ว</span>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <a href="{{asset($customer_deposit->slip_url)}}"
                                            title="สลิปการโอนเงิน"
                                            data-toggle="modal"
                                            data-target="#slipModal_{{$customer_deposit->id}}">
                                            <i class="fa fa-image text-white"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" @if($customer_deposit->status == 0) class="btn btn-primary" @else class="btn btn-default" @endif data-toggle="modal" data-target="#depositModal_{{$customer_deposit->id}}">
                                            <i class="fa fa-hand-holding-usd pl-0 pr-0"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelDepositModal_{{$customer_deposit->id}}">
                                            <i class="fa fa-times pl-0 pr-0"></i>
                                        </button>
                                        <div class="modal fade" id="slipModal_{{$customer_deposit->id}}" modal data-backdrop="false" tabindex="-1">
                                            <div class="modal-dialog text-dark" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <img src="{{asset($customer_deposit->slip_url)}}" class="img-fluid img-center" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </td>
                                </tr>
                                <div class="modal fade" id="depositModal_{{$customer_deposit->id}}" modal data-backdrop="false" tabindex="-1">
                                    <div class="modal-dialog text-dark modal-lg" role="document">
                                        <div class="modal-content">
                                            <form action="{{route('agent.deposit.manual')}}" method="post" id="formDepositManual_{{$customer_deposit->id}}">
                                                <input type="hidden" name="customer_deposit_id" value="{{$customer_deposit->id}}">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title text-white" id="exampleModalLabel">เติมเงิน (Manual)</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <p><b>ชื่อลูกค้า : </b> {{$customer_deposit->customer->name}}</p>
                                                            <p><b>เบอร์โทรศัพท์ : </b> {{$customer_deposit->customer->telephone}}</p>
                                                            <p><b>ไลน์ไอดี : </b> {{$customer_deposit->customer->line_id}}</p>
                                                            <p><b>ธนาคารของลูกค้า : </b> 
                                                                <img src="{{asset($customer_deposit->customer->bank->logo)}}" width="25" alt="">
                                                                {{$customer_deposit->customer->bank->name}} 
                                                                {{$customer_deposit->customer->name}}
                                                            </p>
                                                            <p><b>ธนาคารที่โอนเข้า : </b> 
                                                                <img src="{{asset($customer_deposit->bankAccount->bank->logo)}}" width="25" alt="">
                                                                {{$customer_deposit->bankAccount->bank->name}} 
                                                                {{$customer_deposit->bankAccount->name}}
                                                            </p>
                                                            <p><b>จำนวนเงิน : </b>{{number_format($customer_deposit->amount,2)}}</p>
                                                            <p><b>ไอดีในเกมส์ : </b>{{$customer_deposit->username}}</p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <h2 class="text-center">สลิปการโอนเงิน</h2>
                                                            <img src="{{asset($customer_deposit->slip_url)}}" class="img-fluid img-center" width="200" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="pull-right">
                                                                <button type="button" id="btnSubmit_{{$customer_deposit->id}}" onclick="submitDepositManual({{$customer_deposit->id}})" class="btn btn-primary">
                                                                    <i class="fa fa-check"></i>
                                                                    เติมเงิน
                                                                </button>
                                                                <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close">
                                                                    <i class="fa fa-times"></i>
                                                                    ยกเลิก
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="cancelDepositModal_{{$customer_deposit->id}}" modal data-backdrop="false" tabindex="-1">
                                    <div class="modal-dialog text-dark" role="document" style="margin-top: 70px;">
                                        <div class="modal-content bg-danger">
                                            <form action="{{route('agent.deposit.cancel')}}" method="post" id="formCancelDepositManual_{{$customer_deposit->id}}">
                                                <input type="hidden" name="customer_deposit_id" value="{{$customer_deposit->id}}">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title text-white" id="exampleModalLabel">ยกเลิก</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="" class="text-white">หมายเหตุ</label>
                                                            <input type="text" class="form-control" id="remark_{{$customer_deposit->id}}" name="remark" id="remark" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="pull-right">
                                                                <button type="button" id="btnCancel_{{$customer_deposit->id}}" onclick="cancelDepositManual($customer_deposit->id)" class="btn btn-primary">
                                                                    <i class="fa fa-check"></i>
                                                                    ยืนยัน
                                                                </button>
                                                                <button type="button" class="btn btn-warning" data-dismiss="modal">
                                                                    <i class="fa fa-times"></i>
                                                                    ยกเลิก
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div