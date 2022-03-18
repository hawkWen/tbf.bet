<?php
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename=โค้ดเครดิตฟรี_' . date('d/m/y') . '.xls');
header('Pragma: no-cache');
header('Expires: 0');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>โค้ดเครดิตฟรี</title>
</head>

<body>
    <table border="1">
        <thead>
            <tr>
                <th width="400" align="center">{{ $promotion->name }} </th>
            </tr>
            <tr>

                <th width="400" align="center">วันที่ออก {{ date('d/m/Y H:i') }} </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($codes as $code)
                <tr>
                    <td align="center">{{ $code }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
