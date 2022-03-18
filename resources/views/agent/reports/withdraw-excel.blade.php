<?php
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=รายงานการถอนเงิน".date('dmy').".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Export</title>
	</head>
	<body>
        <table border="1">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>เวลา</th>
                    <th>ลูกค้า</th>
                    <th>ธนาคารที่โอน</th>
                    <th>จำนวนเงิน</th>
                    <th>ประเภท</th>
                    <th>สถานะ</th>
                    <th>remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customer_withdraws->sortByDesc('updated_at') as $customer_withdraw)
                    <tr>
                        <td align="center">{{$customer_withdraw->updated_at->format('d/m/Y')}}</td>
                        <td align="center">{{$customer_withdraw->updated_at->format('H:i')}}</td>
                        <td>{{$customer_withdraw->name}} ({{$customer_withdraw->customer->username}})</td>
                        <td>
                            @if(isset($customer_withdraw->bankAccount))
                            <img src="{{asset($customer_withdraw->bankAccount->bank->logo)}}" width="20" class="img-fluid" alt="" width="20">
                            {{$customer_withdraw->bankAccount->bank->name}} {{$customer_withdraw->bankAccount->account}} {{$customer_withdraw->bankAccount->name}}
                            @endif
                        </td>
                        <td align="center">
                            {{number_format($customer_withdraw->amount,2)}} 
                            @if($customer_withdraw->bonus > 0)
                            + <span class="text-success">{{$customer_withdraw->bonus}}</span>
                            @endif
                        </td>
                        <td align="center">
                            @if($customer_withdraw->type_withdraw == 1)
                                <span class="text-danger">MANUAL 
                                    @if($customer_withdraw->user)
                                        ({{$customer_withdraw->user->name}})
                                    @endif
                                </span>
                            @else
                                <span class="text-success">BOT</span>
                            @endif
                        </td>
                        <td align="center">
                            @if($customer_withdraw->status == 0)
                                <span class="text-warning">รอพนักงานอนุมัติ</span>
                            @elseif($customer_withdraw->status == 1)
                                <span class="">พนักงานถอน</span>    
                            @elseif($customer_withdraw->status == 2)
                                <span class="text-success">ถอนเรียบร้อย</span>    
                            @elseif($customer_withdraw->status == 3)
                                <span class="text-warning">บอทปิดทำงาน</span>    
                            @elseif($customer_withdraw->status == 4)
                                <span class="text-danger">API ERROR</span>  
                            @elseif($customer_withdraw->status == 5)
                                <span class="text-danger">ยกเลิก</span>  
                            @endif
                        </td>
                        <td>
                            {{$customer_withdraw->remark}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
	</body>
</html>