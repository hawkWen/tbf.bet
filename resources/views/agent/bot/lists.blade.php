<div class="clearfix"></div>
<div class="row">
    <div class="col-lg-7">
        <h2>รายการโอนเงิน</h2>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ธนาคาร</th>
                    <th>เวลาโอน</th>
                    <th>เลขที่บัญชี</th>
                    <th>จำนวนเงิน</th>
                    <th>พนักงาน</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bank_account_transactions->sortByDesc('created_at')->take(40) as $transaction)
                    <tr>
                        <td>
                            <img src="{{ asset($transaction->bank->logo) }}" alt="" width="25">
                            {{ $transaction->bank->name }}
                        </td>
                        <td>
                            {{ $transaction->transfer_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td>
                            {{ $transaction->code_bank }}
                            {{ $transaction->bank_account }}
                        </td>
                        <td align="right">
                            {{ number_format($transaction->amount, 2) }}
                        </td>
                        <td align="center">
                            @if ($transaction->user)
                                {{ $transaction->user->name }}
                            @else
                                BOT
                            @endif
                        </td>
                        <td align="center">
                            @if ($transaction->status == 0)
                                <span>
                                    <i class="fas fa-robot mr-2"></i>
                                    รอบอทเติมเงิน
                                </span>
                            @elseif($transaction->status == 1)
                                <span>
                                    <i class="fa fa-user mr-2"></i>
                                    รอพนักงานเติม
                                </span>
                            @elseif($transaction->status == 2)
                                <span class="text-success">
                                    <i class="fa fa-check mr-2"></i>
                                    เติมเงินเสร็จแล้ว
                                </span>
                            @elseif($transaction->status == 3)
                                <span class="text-warning">
                                    <i class="fa fa-exclamation-circle mr-2"></i>
                                    API ERROR
                                </span>
                            @elseif($transaction->status == 4)
                                <span class="text-danger mr-2">
                                    <i class="fa fa-times"></i>
                                    ไม่พบบัญชี
                                </span>
                            @elseif($transaction->status == 5)
                                <span class="text-danger mr-2">
                                    <i class="fa fa-times"></i>
                                    ข้าม
                                </span>
                            @elseif($transaction->status == 6)
                                <span class="text-danger mr-2">
                                    <i class="fa fa-times"></i>
                                    เบิ้ล
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-lg-5">
        <div class="col-lg-12">
            <h2>รายการเติมเงิน</h2>
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>เวลาที่เติม</th>
                        <th>จำนวนเงิน</th>
                        <th>Username</th>
                        <th>ลูกค้าชื่อ</th>
                        <th>ประเภทการเติม</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer_deposits->sortByDesc('created_at') as $customer_deposit)
                        <tr>
                            <td>{{ $customer_deposit->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $customer_deposit->amount }}</td>
                            <td>
                                {{ $customer_deposit->username }}
                            </td>
                            <td>{{ $customer_deposit->name }}</td>
                            <td align="center">
                                @if ($customer_deposit->user_id == 0)
                                    BOT
                                @else
                                    {{ $customer_deposit->user->name }}
                                @endif
                            </td>
                            <td>
                                @if ($customer_deposit->status == 1)
                                    <span class="text-success">สำเร็จ</span>
                                @else
                                    <span class="text-warning">ไม่สำเร็จ</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-lg-12">
            <h2>รายการถอนเงิน</h2>
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>เวลาที่ถอน</th>
                        <th>จำนวนเงิน</th>
                        <th>Username</th>
                        <th align="center">ประเภทการถอน</th>
                        <th align="center">สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer_withdraws->sortByDesc('created_at') as $customer_withdraw)
                        <tr>
                            <td>{{ $customer_withdraw->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $customer_withdraw->amount }}</td>
                            <td>{{ $customer_withdraw->username }}</td>
                            <td>
                                @if ($customer_withdraw->type_withdraw == 1)
                                    BOT
                                @else
                                    MANUAL
                                @endif
                            </td>
                            <td>
                                @if ($customer_withdraw->status == 0)
                                    <span class="text-warning">รอการถอนเงิน</span>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
