<h3> <i class="fa fa-desktop"></i> มอนิเตอร์</h3>
<table class="table table-bordered table-monitor">
    <thead>
        <tr>
            <th>การทำงาน</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bot_events as $bot_event)
            <tr>
                <td>{{$bot_event->event}}</td>
            </tr>
        @endforeach
    </tbody>
</table>