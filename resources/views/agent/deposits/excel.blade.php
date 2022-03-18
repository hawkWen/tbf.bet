<?php
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=รายงานการเติมเงิน".date('dmy').".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <title>รายงานการเติมเงิน</title>
	</head>
	<body>
        <table border="1">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>เวลา</th>
                    <th>ลูกค้า</th>
                    <th>ธนาคารที่โอน</th>
                    <th>โปรโมชั่น</th>
                    <th>จำนวนเงิน</th>
                    <th>ประเภท</th>
                    <th>สถานะ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customer_deposits->sortByDesc('updated_at') as $customer_deposit)
                    <tr>
                        <td align="center">{{$customer_deposit->updated_at->format('d/m/Y')}}</td>
                        <td align="center">{{$customer_deposit->updated_at->format('H:i')}}</td>
                        <td>{{$customer_deposit->name}} ({{$customer_deposit->customer->username}})</td>
                        <td>
                            @if(isset($customer_deposit->bankAccount))
                            <img src="{{asset($customer_deposit->bankAccount->bank->logo)}}" width="20" class="img-fluid" alt="" width="20">
                            {{$customer_deposit->bankAccount->bank->name}} {{$customer_deposit->bankAccount->account}} {{$customer_deposit->bankAccount->name}}
                            @endif
                        </td>
                        <td>
                            @if($customer_deposit->promotion)
                                {{$customer_deposit->promotion->name}}
                            @else
                                -
                            @endif  
                        </td>
                        <td align="center">
                            {{number_format($customer_deposit->amount,2)}} 
                            @if($customer_deposit->bonus > 0)
                            + <span class="text-success">{{$customer_deposit->bonus}}</span>
                            @endif
                        </td>
                        <td align="center">
                            @if($customer_deposit->type_deposit == 1)
                                <span class="text-danger">MANUAL 
                                    @if($customer_deposit->user)
                                        ({{$customer_deposit->user->name}})
                                    @endif
                                </span>
                            @else
                                <span class="text-success">BOT</span>
                            @endif
                        </td>
                        <td align="center">
                            @if($customer_deposit->status == 0)
                                <span class="text-warning">รอเติมเงิน</span>
                            @elseif ($customer_deposit->status == 1)
                                <span class="text-success">เติมสำเร็จ</span>
                            @elseif ($customer_deposit->status == 2)
                                <span class="text-danger">{{$input['remark']}}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
	</body>
</html>