<?php
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename=รายงานการเติมเงิน/ถอนเงิน' . date('dmy') . '.xls');
header('Pragma: no-cache');
header('Expires: 0');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>รายงานการเติมเงิน/ถอนเงิน</title>
</head>

<body>
    <table border="1">
        <thead>
            <tr>
                <th>แบรนด์</th>
                <th>ยอดฝาก</th>
                <th>ยอดฝาก</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report['brand'] }}</td>
                    <td align="center" class="text-success">
                        {{ number_format($report['deposit'], 2) }}</td>
                    <td align="center" class="text-danger">
                        {{ number_format($report['withdraw'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
