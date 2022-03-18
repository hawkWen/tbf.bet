<?php
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename=รายงานโปรโมชั่น' . date('dmy') . '.xls');
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
                <th width="200">วันที่/เวลา</th>
                <th>ชื่อลูกค้า</th>
                <th>โปรโมชั่น</th>
                <th>ยอดเติมเงิน</th>
                <th>จำนวนโบนัส</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotion_costs as $promotion_cost)
                <tr>
                    <td>{{ $promotion_cost->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $promotion_cost->customer->name }} ({{ $promotion_cost->customer->username }})</td>
                    <td>
                        @if ($promotion_cost->promotion)
                            {{ $promotion_cost->promotion->name }}
                        @else
                            โบนัสวงล้อ
                        @endif
                    </td>
                    <td align="right">{{ number_format($promotion_cost->amount, 2) }}</td>
                    <td align="right">{{ number_format($promotion_cost->bonus, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
