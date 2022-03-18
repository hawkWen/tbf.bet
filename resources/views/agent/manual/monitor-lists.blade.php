<span class="badge badge-warning">หมายเหตุ. ถ้าบัญชีธนาคารซ้ำจะมีไอดีมากกว่า 1 ไอดี กรุณาตรวจสอบใบสลิปจากลูกค้า</span>
<hr>
<table class="table table-bordered">
    <tr class="bg-warning">
        <th>วันที่/เวลา</th>
        <th>ธนาคาร</th>
        <th>ไอดีลูกค้า</th>
        <th>จำนวนเงิน</th>
        <th>สถานะ</th>
    </tr>
    <tbody>
        @foreach ($bank_account_transactions as $bank_account_transaction)
            <tr>
                <td width="50">
                    {{ $bank_account_transaction->created_at->format('d/m/Y H:i:s') }}
                </td>
                <td>
                    @if ($bank_account_transaction->code == 'X1')
                        {{ $bank_account_transaction->code_bank }} /
                        {{ $bank_account_transaction->bank_account }}
                    @else
                        {{ $bank_account_transaction->description }}
                    @endif
                </td>
                <td>
                    @if ($bank_account_transaction->bank_id == 1)
                        @if ($bank_account_transaction->scbCustomers->where('brand_id', '=', $bank_account_transaction->brand_id)->count() > 1)
                            <span class="badge badge-warning"> บัญชีธนาคารซ้ำ</span>
                        @endif
                        <br>
                        @foreach ($bank_account_transaction->scbCustomers->where('brand_id', '=', $bank_account_transaction->brand_id) as $key => $scb_customer)
                            {{ $scb_customer->username }} <br>
                        @endforeach
                    @elseif($bank_account_transaction->bank_id == 0)

                        @php
                            
                            $telephone = $bank_account_transaction->bank_account;
                            
                            $t1 = substr($telephone, 0, 3);
                            
                            $t2 = substr($telephone, 3);
                            
                            $telephone = $t1 . '-' . $t2;
                            //truemoney
                            $customer_truemoney = App\Models\Customer::whereBrandId($bank_account_transaction->brand_id)
                                ->whereTelephone($telephone)
                                ->where('status_manual', '=', 0)
                                ->first();
                            
                            echo $customer_truemoney->username;
                        @endphp
                    @endif
                </td>
                <td align="right">
                    {{ number_format($bank_account_transaction->amount, 2) }}
                </td>
                <td align="center">
                    @if ($bank_account_transaction->status == 0)
                        <span class="text-info">
                            <i class="fas fa-robot mr-2"></i>
                            รอบอทเติมเงิน
                        </span>
                    @elseif($bank_account_transaction->status == 1)
                        <span class="text-success">
                            <i class="fa fa-check mr-2"></i>
                            เติมเงินเสร็จแล้ว
                        </span>
                        <span class="text-center">
                            @if ($bank_account_transaction->deposit)
                                <p>
                                    {{ $bank_account_transaction->deposit->customer->username }}
                                </p>
                            @endif
                        </span>
                    @elseif($bank_account_transaction->status == 2)
                        <span class="text-warning mr-2">
                            <i class="far fa-clock"></i>
                            กำลังเชื่อมต่อ API
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
