<table class="table table-bordered">
    <thead>
        <tr>
            <td>แบรนด์</td>
            <td>บัญชีธนาคาร</td>
            <td>ประเภท</td>
            <td>สถานะ</td>
            <td>Last Execution Time</td>
            <td>อัพเดทล่าสุด</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($bank_accounts as $bank_account)
            <tr>
                <td>{{ $bank_account->brand->subdomain }}</td>
                <td>
                    <p>{{ $bank_account->name }}</p>
                    <p>{{ $bank_account->bank_account }}</p>
                </td>
                <td>
                    @if ($bank_account->type == 0)
                        <span class="text-success">เข้า/ออก (auto)</span>
                    @elseif($bank_account->type == 1)
                        <span class="text-success">ขาเข้า (auto)</span>
                    @elseif($bank_account->type == 2)
                        <span class="text-danger">ขาเข้าสำรอง (manual)</span>
                    @elseif($bank_account->type == 3)
                        <span class="text-warning">ขาออก (auto)</span>
                    @elseif($bank_account->type == 4)
                        <span class="text-primary">ขาออกสำรอง (manual)</span>
                    @elseif($bank_account->type == 5)
                        <span class="text-primary">กลาง</span>
                    @elseif($bank_account->type == 6)
                        <span class="text-primary">ขาเข้า SCB EASY</span>
                    @elseif($bank_account->type == 7)
                        <span class="text-danger">ขาออก SCB EASY</span>
                    @elseif($bank_account->type == 8)
                        <span class="text-danger">truemoney manual</span>
                    @elseif($bank_account->type == 9)
                        <span class="text-success">SCB PIN ขาเข้า</span>
                    @elseif($bank_account->type == 10)
                        <span class="text-danger">SCB PIN ขาออก</span>
                    @elseif($bank_account->type == 11)
                        <span class="text-danger">SCB PIN ขาเข้า/ขาออก</span>
                    @endif
                </td>
                <td>
                    @if ($bank_account->active == 0)
                        <span class="text-success">Active</span>
                    @elseif($bank_account->active == 1)
                        <span class="text-success">Wait</span>
                    @endif
                </td>
                <td>
                    {{ number_format($bank_account->last_execution_time, 2) }} s
                </td>
                <td>
                    {{ $bank_account->updated_at->format('d/m/Y H:i:s') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
