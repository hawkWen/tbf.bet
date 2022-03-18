<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bot Withdraw</title>
</head>

<body>

    <div class="flash-message">
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>

    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if (Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }} mb-0 text-white">
                    {{ Session::get('alert-' . $msg) }}
                </p>
            @endif
        @endforeach
    </div>
    <form action="{{ route('otp.withdraw') }}" method="post">
        <input type="hidden" name="bank_account_id" value="38">
        <h2>ระบบโอนเงิน</h2>
        <p>เลขบัญชี : <input type="text" name="accno"></p>
        <p>ธนาคาร : <select name="bankno">
                <option value="001">ธนาคารไทยพาณิชย์</option>
                <option value="002">ธนาคารกรุงเทพ</option>
                <option value="004">ธนาคารกสิกรไทย</option>
                <option value="006">ธนาคารกรุงไทย</option>
                <option value="011">ธนาคารทหารไทย</option>
                <option value="025">ธนาคารกรุงศรีอยุธยา</option>
                <option value="030">ธนาคารออมสิน</option>
                <option value="065">ธนาคารธนชาติ</option>
            </select></p>
        <p>จำนวนเงิน : <input type="text" name="amount"></p>
        <p><input type="submit" value="โอนเงิน">
        </p>
    </form>
</body>

</html>
