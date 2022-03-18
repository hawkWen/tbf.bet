<?php

use App\Helpers\Helper;
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename=รายงานลูกค้า' . date('dmy') . '.xls');
header('Pragma: no-cache');
header('Expires: 0');
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
                <th width="200">วันที่สมัคร</th>
                <th>ชื่อลูกค้า</th>
                <th>ข้อมูลธนาคาร</th>
                @if (Auth::user()->user_role_id == 4)
                    <th>เบอร์โทรศัพท์</th>
                    <th>ไลน์ไอดี</th>
                @endif
                <th>ช่องทางการรู้จัก</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>
                        {{ $customer->name }}
                    </td>
                    <td>
                        {{ $customer->bank->name }} {{ $customer->bank_account }}
                    </td>

                    @if (Auth::user()->user_role_id == 4)
                        <td>
                            {{ Helper::decryptString($customer->telephone, 1, 'base64') }}
                        </td>
                        <td>
                            {{ Helper::decryptString($customer->line_id, 1, 'base64') }}
                        </td>
                    @endif
                    <td>
                        <p>{{ $customer->from_type }}</p>
                        <small>{{ $customer->from_type_remark }}</small>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
