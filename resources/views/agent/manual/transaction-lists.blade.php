<div class="accordion" id="accordionExample">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h5 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    รายการเติมเงินล่าสุด
                </button>
            </h5>
        </div>

        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="card-body" style="padding: 0px !important;">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped">

                            <tr class="bg-warning">
                                <th>วันที่/เวลา</th>
                                <th>ธนาคาร</th>
                                <th>จำนวนเงิน</th>
                                <th>สถานะ</th>
                            </tr>
                            <tbody>
                                @foreach ($bank_account_transactions as $bank_account_transaction)
                                    <tr>
                                        <td width="50">
                                            {{ $bank_account_transaction->created_at->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td>
                                            @if ($bank_account_transaction->code == 'X1')
                                                {{ $bank_account_transaction->code_bank }} /
                                                {{ $bank_account_transaction->bank_account }}
                                            @else
                                                {{ $bank_account_transaction->description }}
                                            @endif
                                        </td>
                                        <td align="right">
                                            {{ number_format($bank_account_transaction->amount, 2) }}
                                        </td>
                                        <td align="center">
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
                                                            {{ $bank_account_transaction->deposit->customer->username }}
                                                        </p>
                                                    @endif
                                                </span>
                                            @elseif($bank_account_transaction->status == 2)
                                                <span class="text-warning mr-2">
                                                    <i class="far fa-clock"></i>
                                                    กำลังเชื่อมต่อ API
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="false" aria-controls="collapseTwo">
                    รายการเติมผิดพลาดล่าสุด
                </button>
            </h5>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
            <div class="card-body" style="padding: 0px !important">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped">

                            <tr class="bg-warning">
                                <th>วันที่</th>
                                <th>เลขที่บัญชี</th>
                                <th>สถานะ</th>
                            </tr>
                            @foreach ($bank_account_transaction_wrongs as $bank_account_transaction_wrong)
                                <tr class="">
                                    <td width="100">

                                        {{ $bank_account_transaction_wrong->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td>
                                        {{ $bank_account_transaction_wrong->code_bank }} /
                                        {{ $bank_account_transaction_wrong->bank_account }}
                                    </td>
                                    <td align="center">
                                        @if ($bank_account_transaction_wrong->status == 4)
                                            <span class="badge badge-warning">
                                                ไม่พบบัญชีนี้ในระบบ
                                            </span>
                                        @elseif($bank_account_transaction_wrong->status == 5)
                                            <span class="badge badge-warning">
                                                รายการนี้เติมมือแล้ว
                                            </span>
                                        @elseif($bank_account_transaction_wrong->status == 6)
                                            <span class="badge badge-warning">
                                                ติดโปรโมชั่น
                                            </span>
                                        @elseif($bank_account_transaction_wrong->status == 8)
                                            <span class="badge badge-warning">
                                                เลขที่บัญชี SCB 4 หลักซ้ำกัน
                                            </span>
                                        @elseif($bank_account_transaction_wrong->status == 9)
                                            <span class="badge badge-warning">
                                                ลูกค้าออนไลน์อยู่
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="card">
        <div class="card-header" id="headingThree">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree"
                    aria-expanded="false" aria-controls="collapseThree">
                    Collapsible Group Item #3
                </button>
            </h5>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf
                moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda
                shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea
                proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim
                aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
            </div>
        </div>
    </div> --}}
</div>

<div class="row">
    {{-- <div class="col-12">
        <div class="">
            <h3>รายการเติมที่ผิดพลาดล่าสุด</h3>
            <hr>
            <table class="table table-striped">

                <tr class="bg-warning">
                    <th>วันที่</th>
                    <th>เลขที่บัญชี</th>
                    <th>สถานะ</th>
                </tr>
                @foreach ($bank_account_transactions->whereIn('status', [4, 5, 6, 8, 9])->sortByDesc('created_at')->take(7)
    as $bank_account_transaction)
                    <tr class="">
                        <td width="100">

                            {{ $bank_account_transaction->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td>
                            {{ $bank_account_transaction->code_bank }} /
                            {{ $bank_account_transaction->bank_account }}
                        </td>
                        <td align="center">
                            @if ($bank_account_transaction->status == 4)
                                <span class="badge badge-warning">
                                    ไม่พบบัญชีนี้ในระบบ
                                </span>
                            @elseif($bank_account_transaction->status == 5)
                                <span class="badge badge-warning">
                                    รายการนี้เติมมือแล้ว
                                </span>
                            @elseif($bank_account_transaction->status == 6)
                                <span class="badge badge-warning">
                                    ติดโปรโมชั่น
                                </span>
                            @elseif($bank_account_transaction->status == 8)
                                <span class="badge badge-warning">
                                    เลขที่บัญชี SCB 4 หลักซ้ำกัน
                                </span>
                            @elseif($bank_account_transaction->status == 9)
                                <span class="badge badge-warning">
                                    ลูกค้าออนไลน์อยู่
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>
    <div class="col-12">
        <div class="">
            <h3>รายการเติมล่าสุด</h3>
            <hr>
            <table class="table table-striped">

                <tr class="bg-success">
                    <th>วันที่</th>
                    <th>เลขที่บัญชี</th>
                    <th>ไอดี</th>
                    <th>สถานะ</th>
                </tr>
                @foreach ($bank_account_transactions->whereIn('status', [0, 1, 2])->sortByDesc('created_at')->take(7)
    as $bank_account_transaction)
                    <tr class="">
                        <td>

                            {{ $bank_account_transaction->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td>
                            {{ $bank_account_transaction->code_bank }} /
                            {{ $bank_account_transaction->bank_account }}
                        </td>
                        <td>
                            @if ($bank_account_transaction->deposit)
                                {{ $bank_account_transaction->deposit->username }}
                            @endif
                        </td>
                        <td align="center">
                            @if ($bank_account_transaction->status == 0)
                                <span class="badge badge-light">
                                    รอการเติมเงิน
                                </span>
                            @elseif($bank_account_transaction->status == 1)
                                <span class="badge badge-success">
                                    เติมเงินเสร็จแล้ว
                                </span>
                            @elseif($bank_account_transaction->status == 2)
                                <span class="badge badge-warning">
                                    กำลังเติมเงิน
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div> --}}

</div>
