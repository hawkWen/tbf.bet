<input type="hidden" id="withdrawCount" value="{{ $customer_withdraws->whereNotIn('status', [2, 5])->count() }}">
<div class="row">
    <div class="col-lg-12">
        <h3>รายการธนาคารขาออก</h3>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-danger text-white">
                    <th>บัญชีธนาคาร</th>
                    <th>ยอดเงิน</th>
                    <th>ประเภท</th>
                    <th>สถานะบอท</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bank_accounts as $bank_account)
                    <tr
                        style="background-color: {{ $bank_account->bank->bg_color }};color: {{ $bank_account->bank->font_color }};">
                        <td>
                            <img src="{{ asset($bank_account->bank->logo) }}" width="20" class="img-fluid" alt=""
                                width="20">
                            {{ $bank_account->bank->name }} {{ $bank_account->name }} {{ $bank_account->account }}
                        </td>
                        <td align="center">{{ $bank_account->amount }}</td>
                        <td align="center">
                            @if ($bank_account->type == 3)
                                <span class="text-white">ขาออก (auto)</span>
                            @elseif($bank_account->type == 4)
                                <span class="text-white">ขาออกสำรอง (manual)</span>
                            @endif
                        </td>
                        <td align="center">
                            @if ($bank_account->type == 3)
                                @if ($bank_account->status_bot == 1)
                                    <span class="text-success">BOT</span>
                                @else
                                    <span class="text-warning">ปิดบอท</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-lg-4">
        <h4>บอทถอน 5 รายการล่าสุด</h4>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-primary text-white">
                    <th>วันที่/เวลา</th>
                    <th>Username</th>
                    <th>จำนวนเงิน</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customer_withdraws->where('type_withdraw', '=', 1)->where('status', '=', 2)->sortByDesc('created_at')->take(5)
    as $customer_withdraw)
                    <tr>
                        <td>{{ $customer_withdraw->created_at->format('d/m/y H:i:s') }}</td>
                        <td>{{ $customer_withdraw->username }}</td>
                        <td align="center">{{ number_format($customer_withdraw->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h4>พนักงานถอน 5 รายการล่าสุด</h4>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-primary text-white">
                    <th>วันที่/เวลา</th>
                    <th>Username</th>
                    <th>จำนวนเงิน</th>
                    <th>พนักงาน</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customer_withdraws->where('type_withdraw', '=', 2)->where('status', '=', 2)->sortByDesc('created_at')->take(5)
    as $customer_withdraw)
                    <tr>
                        <td>{{ $customer_withdraw->created_at->format('d/m/y H:i:s') }}</td>
                        <td>{{ $customer_withdraw->username }}</td>
                        <td align="center">{{ number_format($customer_withdraw->amount, 2) }}</td>
                        <td>
                            @if ($customer_withdraw->user)
                                {{ $customer_withdraw->user->name }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-lg-8">
        <h4>รายการถอนที่รอการอนุมัติ</h4>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-warning">
                    <th>วันที่ทำรายการ</th>
                    <th>Username</th>
                    <th>โปรโมชั่นที่รับล่าสุด</th>
                    <th align="center">จำนวนเงินที่ถอน</th>
                    <th>สถานะ</th>
                    <th>อนุมัติ</th>
                    <th>ยกเลิก</th>
                    <th>รีเซ็ต</th>
                    <th>ถอนมือ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customer_withdraws->where('type_withdraw', '!=', 1)->whereNotIn('status', [2, 5])->sortByDesc('created_at')
    as $customer_withdraw)
                    <tr>
                        <td>{{ $customer_withdraw->created_at->format('d/m/y H:i:s') }}</td>
                        <td>
                            <a onclick="alert('{{ $customer_withdraw->name }}')" style="cursor: pointer">
                                {{ $customer_withdraw->username }}
                            </a>
                            <p>
                                <small>
                                    @if ($customer_withdraw->type_withdraw == 2)
                                        บัญชีธนาคารลูกค้า: {{ $customer_withdraw->customer->bank->name }}
                                        {{ $customer_withdraw->customer->bank_account }}
                                        {{ $customer_withdraw->name }}
                                    @endif
                                </small>
                            </p>
                        </td>
                        <td>
                            @if ($customer_withdraw->promotion)
                                <a onclick="alert('{{ $customer_withdraw->promotion->name }}')"
                                    style="cursor: pointer">
                                    <div class="cut-text">{{ $customer_withdraw->promotion->name }}</div>
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td align="center">
                            {{ number_format($customer_withdraw->amount, 2) }}
                        </td>
                        <td align="center">
                            @if ($customer_withdraw->status == 0)
                                <span class="text-warning">รอพนักงานอนุมัติ</span>
                            @elseif($customer_withdraw->status == 1)
                                <span class="">พนักงานถอน</span>
                            @elseif($customer_withdraw->status == 2)
                                <span class="text-success">ถอนเรียบร้อย</span>
                            @elseif($customer_withdraw->status == 3)
                                <span class="text-warning">บอทปิดทำงาน</span>
                            @elseif($customer_withdraw->status == 4)
                                <span class="text-danger">API ERROR</span>
                            @endif
                        </td>
                        <td width="50">
                            @if ($customer_withdraw->status == 0 || $customer_withdraw->status == 1 || $customer_withdraw->promotion)
                                <span style="display: block;margin: 0 auto;vertical-align: middle;">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#approveModal_{{ $customer_withdraw->id }}">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </span>
                                <div class="modal fade" id="approveModal_{{ $customer_withdraw->id }}" modal
                                    data-backdrop="false" tabindex="-1">
                                    <div class="modal-dialog text-dark" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('agent.withdraw.approve') }}" method="post"
                                                data-form="approve">
                                                <div class="modal-body">
                                                    <input type="hidden" name="customer_withdraw_id"
                                                        value="{{ $customer_withdraw->id }}" />
                                                    <input type="hidden" name="promotion_id"
                                                        value="{{ $customer_withdraw->promotion_id }}" />
                                                    <h4>ตรวจสอบการถอนเงิน</h4>
                                                    <hr>
                                                    <p><b>Username</b>: {{ $customer_withdraw->username }}</p>
                                                    <p><b>ชื่อลูกค้า</b>: {{ $customer_withdraw->name }}</p>
                                                    @if ($customer_withdraw->promotionCost)
                                                        <p><b>โปรโมชั่นที่รับล่าสุด: </b>
                                                            {{ $customer_withdraw->promotionCost->promotion->name }}
                                                        </p>
                                                        <p><b>ประเภทโปรโมชั่น: </b>
                                                            @if ($customer_withdraw->promotionCost->promotion->type_promotion_cost == 1)
                                                                ชิบเป็น
                                                            @elseif($customer_withdraw->promotionCost->promotion->type_promotion_cost
                                                                == 2)
                                                                ชิบตาย (ดึงโบนัสคืน)
                                                            @elseif($customer_withdraw->promotionCost->promotion->type_promotion_cost
                                                                == 3)
                                                                ได้รับโบนัสหลังจากทำเทิร์นครับ
                                                            @endif
                                                        </p>
                                                        <p><b>จำนวนเงินที่เติม: </b>
                                                            {{ $customer_withdraw->promotionCost->amount }}</p>
                                                        <p><b>โบนัส: </b>
                                                            {{ $customer_withdraw->promotionCost->bonus }}</p>
                                                        <p><b>เวลาที่รับโปรโมชั่น: </b>
                                                            {{ $customer_withdraw->promotionCost->created_at->format('d/m/Y H:i:s') }}
                                                        </p>
                                                    @else
                                                        <p><b>โปรโมชั่นที่รับล่าสุด: </b> - </p>
                                                    @endif
                                                    @if ($customer_withdraw->promotionCost)
                                                        @if ($customer_withdraw->promotionCost->promotion->type_promotion_cost == 2)
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="">ดึงโบนัสออกจำนวน</label>
                                                                    <input type="text" class="form-control"
                                                                        name="bonus" value="0"
                                                                        input-type="money_decimal">
                                                                </div>
                                                            </div>
                                                        @elseif($customer_withdraw->promotionCost->promotion->type_promotion_cost
                                                            == 3)
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="">ได้รับโบนัสเพิ่มจำนวน</label>
                                                                    <input type="text" class="form-control"
                                                                        name="bonus" value="0"
                                                                        input-type="money_decimal">
                                                                </div>
                                                            </div>
                                                        @else
                                                            <input type="hidden" class="form-control" name="bonus"
                                                                value="0" input-type="money_decimal">
                                                        @endif
                                                    @else
                                                        <input type="hidden" class="form-control" name="bonus"
                                                            value="0" input-type="money_decimal">
                                                    @endif
                                                    <hr>
                                                    <h2>รายการที่รับล่าสุด</h2>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <td>วันที่เติมเงิน</td>
                                                                <td>โปรโมชั่นที่รับล่าสุด</td>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                    <div class="pull-left">
                                                        <p><b>พนักงานที่ตรวจสอบ:</b> {{ Auth::user()->name }}</p>
                                                    </div>
                                                    <div class="pull-right">
                                                        <button type="submit" class="btn btn-primary">
                                                            ยืนยัน
                                                        </button>
                                                        <button type="button" class="btn btn-warning"
                                                            data-dismiss="modal">
                                                            ยกเลิก
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button class="btn btn-primary btn-sm" disabled>
                                    <i class="fa fa-check"></i>
                                </button>
                            @endif
                        </td>
                        <td width="50">
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#cancelModal_{{ $customer_withdraw->id }}">
                                <i class="fa fa-times"></i>
                            </button>
                            <div class="modal fade" id="cancelModal_{{ $customer_withdraw->id }}" modal
                                data-backdrop="false" tabindex="-1">
                                <div class="modal-dialog text-dark" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('agent.withdraw.cancel') }}" method="post"
                                            data-form="cancel">
                                            <input type="hidden" name="customer_withdraw_id"
                                                value="{{ $customer_withdraw->id }}" />
                                            <div class="modal-body">
                                                <h4>ยกเลิกการถอนเงิน</h4>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label for="">หมายเหตุ</label>
                                                        <input type="text" class="form-control" name="remark"
                                                            required>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="pull-left">
                                                    <p><b>พนักงานที่ตรวจสอบ:</b> {{ Auth::user()->name }}</p>
                                                </div>
                                                <div class="pull-right">
                                                    <button type="submit" class="btn btn-primary">
                                                        ยืนยัน
                                                    </button>
                                                    <button type="button" class="btn btn-warning" data-dismiss="modal">
                                                        ยกเลิก
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td width="50">
                            @if ($customer_withdraw->status == 3 || $customer_withdraw->status == 4)
                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                                    data-target="#refreshModal_{{ $customer_withdraw->id }}">
                                    <i class="fa fa-sync-alt"></i>
                                </button>
                                <div class="modal fade" id="refreshModal_{{ $customer_withdraw->id }}" modal
                                    data-backdrop="false" tabindex="-1">
                                    <div class="modal-dialog text-dark" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('agent.withdraw.refresh') }}" method="post"
                                                data-form="refresh">
                                                <input type="hidden" name="customer_withdraw_id"
                                                    value="{{ $customer_withdraw->id }}" />
                                                <div class="modal-body">
                                                    <h4>รีเฟรชบอท</h4>
                                                    <hr>
                                                    <div class="pull-left">
                                                        <p><b>พนักงานที่ตรวจสอบ:</b> {{ Auth::user()->name }}</p>
                                                    </div>
                                                    <div class="pull-right">
                                                        <button type="submit" class="btn btn-primary">
                                                            ยืนยัน
                                                        </button>
                                                        <button type="button" class="btn btn-warning"
                                                            data-dismiss="modal">
                                                            ยกเลิก
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fa fa-sync-alt"></i>
                                </button>
                            @endif
                        </td>
                        <td width="50">
                            @if ($customer_withdraw->type_withdraw == 2)
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#manualModal_{{ $customer_withdraw->id }}">
                                    <i class="fa fa-hand-paper"></i>
                                </button>
                                <div class="modal fade" id="manualModal_{{ $customer_withdraw->id }}" modal
                                    data-backdrop="false" tabindex="-1">
                                    <div class="modal-dialog text-dark" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('agent.withdraw.manual') }}" method="post"
                                                data-form="manual">
                                                <input type="hidden" name="customer_withdraw_id"
                                                    value="{{ $customer_withdraw->id }}" />
                                                <div class="modal-body">
                                                    <h4>ถอนมือ</h4>
                                                    <hr>
                                                    <p>
                                                        <b>บัญชีธนาคารลูกค้า:</b>
                                                        {{ $customer_withdraw->customer->bank->name }}
                                                        {{ $customer_withdraw->customer->bank_account }}
                                                        {{ $customer_withdraw->name }}
                                                    </p>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="">ธนาคารที่ใช้ถอน</label>
                                                            <select name="bank_account_id" id="bank_account_id"
                                                                class="form-control" required>
                                                                <option value="">เลือก</option>
                                                                @foreach ($bank_accounts as $bank_account)
                                                                    <option value="{{ $bank_account->id }}">
                                                                        {{ $bank_account->bank->name }}
                                                                        {{ $bank_account->account }}
                                                                        {{ $bank_account->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <label for="">จำนวนเงินที่โอน</label>
                                                            <input type="text" class="form-control" name="amount"
                                                                input-type="money_decimal" value="0.00">
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="pull-left">
                                                        <p><b>พนักงานที่ตรวจสอบ:</b> {{ Auth::user()->name }}</p>
                                                    </div>
                                                    <div class="pull-right">
                                                        <button type="submit" class="btn btn-primary">
                                                            ยืนยัน
                                                        </button>
                                                        <button type="button" class="btn btn-warning"
                                                            data-dismiss="modal">
                                                            ยกเลิก
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button class="btn btn-warning btn-sm" disabled>
                                    <i class="fa fa-hand-paper"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
