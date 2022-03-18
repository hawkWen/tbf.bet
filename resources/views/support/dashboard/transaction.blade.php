<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>วันที่/เวลา</th>
            <th>แบรนด์</th>
            <th>statementกำกับ</th>
            <th>สถานะ</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bank_account_transactions as $bank_account_transaction)
            <tr>
                <td align="center">
                    {{ $bank_account_transaction->created_at->format('d/m/Y') }}
                    <br>
                    {{ $bank_account_transaction->created_at->format('H:i:s') }}
                </td>
                <td>{{ $bank_account_transaction->brand->name }}</td>
                <td>{{ $bank_account_transaction->bank_account }} /
                    {{ $bank_account_transaction->amount }}</td>
                <td>
                    @if ($bank_account_transaction->status == 0)
                        <span class="text-info">
                            <i class="fas fa-robot mr-2"></i>
                            รอบอทเติมเงิน
                        </span>
                    @elseif($bank_account_transaction->status == 1)
                        <span class="text-success">
                            <i class="fa fa-check mr-2"></i>
                            เติมเงินเสร็จแล้ว
                        </span>
                        <span class="text-center">
                            @if ($bank_account_transaction->deposit)
                                <p>
                                    ลูกค้า:
                                    {{ $bank_account_transaction->deposit->customer->username }}
                                </p>
                            @endif
                        </span>
                    @elseif($bank_account_transaction->status == 2)
                        <span class="text-warning mr-2">
                            <i class="far fa-clock"></i>
                            กำลังเชื่อมต่อ API
                        </span>
                    @elseif($bank_account_transaction->status == 3)
                        <span class="text-danger mr-2">
                            <i class="fa fa-times"></i>
                            เบิ้ล
                        </span>
                    @elseif($bank_account_transaction->status == 4)
                        <span class="text-danger mr-2">
                            <i class="fa fa-times"></i>
                            ไม่พบบัญชีนี้ในระบบ
                        </span>
                    @elseif($bank_account_transaction->status == 5)
                        <span class="text-warning mr-2">
                            <i class="fa fa-times"></i>
                            รายการนี้เติมมือแล้ว
                        </span>
                    @elseif($bank_account_transaction->status == 6)
                        <span class="text-warning mr-2">
                            <i class="fa fa-times"></i>
                            ติดโปรโมชั่น
                        </span>
                    @elseif($bank_account_transaction->status == 8)
                        <span class="text-warning mr-2">
                            <i class="fa fa-times"></i>
                            เลขที่บัญชี SCB 4 หลักซ้ำกัน
                        </span>
                    @elseif($bank_account_transaction->status == 9)
                        <span class="text-danger mr-2">
                            <i class="fa fa-times"></i>
                            ลูกค้าออนไลน์อยู่
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
