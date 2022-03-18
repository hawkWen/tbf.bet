<?php
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=รายงานการทำงาน".date('dmy').".xls");
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
                <th>พนักงาน</th>
                <th>การทำงาน</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user_events as $user_event)
                <tr>
                    <td width="150" align="center">{{$user_event->created_at->format('d/m/Y H:i:s')}}</td>
                    <td>{{$user_event->user->name}}</td>
                    <td>{{$user_event->description}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>