<?php
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=รายงานการเดินบัญชี".date('dmy').".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>รายงานลูกค้า</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th width="200">วันที่/เวลา</th>
                <th>ธนาคาร</th>
                <th>ประเภท</th>
                <th>จำนวนเงิน</th>
                <th>พนักงานที่ทำรายการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bank_account_histories as $bank_account_history)
                <tr>
                    <td>{{$bank_account_history->created_at->format('d/m/Y H:i:s')}}</td>
                    <td>
                        <img src="{{asset($bank_account_history->bankAccount->bank->logo)}}" width="20" class="img-fluid" alt="" width="20">
                            {{$bank_account_history->bankAccount->bank->name}} {{$bank_account_history->bankAccount->name}}
                    </td>
                    <td>
                        @if($bank_account_history->table == 'customer_deposits')
                            ลูกค้าเติมเงิน 
                        @elseif($bank_account_history->table == 'customer_withdraws')
                            ลูกค้าถอนเงิน
                        @elseif($bank_account_history->table == 'bank_account_withdraws')
                            เบิกจ่าย
                        @elseif($bank_account_history->table == 'bank_account_returns')
                            โอนคืนลูกค้า
                        @elseif($bank_account_history->table == 'bank_account_transfers')
                            โยกเงินเข้า / ออก
                        @elseif($bank_account_history->table == 'bank_account_receives')
                            รับเงินจากสายบน
                        @endif
                    </td>
                    <td align="center">
                        @if($bank_account_history->type == 1)
                            <span class="text-success"> + {{number_format($bank_account_history->amount,2)}}</span>
                        @else
                            <span class="text-danger"> - {{number_format($bank_account_history->amount,2)}}</span>
                        @endif
                    </td>
                    <td align="center">
                        @if($bank_account_history->user)
                            
                        @else
                            BOT
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>