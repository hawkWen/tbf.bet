@extends('layouts.support')

@section('css')
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="d-flex flex-column-fluid">
            {{-- @if (Auth::user()->user_role_id != 3) --}}
            <div class="container-fluid">
                <h3 class="text-dark ">

                    {{-- <div class="clearfix"></div> --}}<i class="fas fa-tachometer-alt mr-3"></i> แผงควบคุมการทำงาน
                </h3>
                <div class="clearfix"></div>
                <hr>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="pull-right">
                            <p> รีเฟรชใน
                                <span id="secondTransaction">5</span> วินาที
                            </p>
                        </div>
                        <h3 class=""> <i class=" fa fa-money-check-alt mr-3"></i>
                            รายการ transaction ล่าสุด</h3>
                        <hr>
                        <div class="card card-custom card-shadowless gutter-b bg-white">

                            <div id="tableTransaction">
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
                                                        @if ($bank_account_transaction->code == 'X1')
                                                            <span class="text-danger mr-2">
                                                                <i class="fa fa-times"></i>
                                                                เบิ้ล
                                                            </span>
                                                        @else
                                                            <span class="text-warning mr-2">
                                                                <i class="fa fa-credit-card"></i>
                                                                ยอดโอนออก
                                                            </span>
                                                        @endif
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
                                                            @if ($bank_account_transaction->customer)
                                                                {{ $bank_account_transaction->customer->username }}
                                                            @endif
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
                    <div class="col-lg-8" id="tableHelper">
                        <div class="pull-right">
                            <p> รีเฟรชใน
                                <span id="secondHelper">30</span> วินาที
                            </p>
                        </div>
                        <h3> <i class="fa fa-question-circle"></i> สถานะบอทธนาคาร</h3>
                        <hr>
                        <div class="card card-custom card-shadowless gutter-b bg-white">

                            <!--begin::Body-->
                            <div class="card-body pt-2" id="refreshBankAccount">
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
                                                <td>
                                                    @if ($bank_account->brand)
                                                        {{ $bank_account->brand->subdomain }}
                                                    @else
                                                        {{ $bank_account->brand_id }}
                                                    @endif
                                                </td>
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
                            </div>
                            <!--end::Body-->
                        </div>
                    </div>
                </div>
            </div>
            {{-- @endif --}}
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        // Class definition
        $(function() {
            // enable clear button 
            $('#kt_datepicker_2, #kt_datepicker_3_validate').datepicker({
                todayBtn: "linked",
                todayHighlight: true,
                format: 'dd/mm/yyyy',
            });
            // enable clear button 
            $('#kt_datepicker_1, #kt_datepicker_3_validate').datepicker({
                todayBtn: "linked",
                todayHighlight: true,
                format: 'dd/mm/yyyy',
            });

            var iSecondTransaction = 5;

            var iSecondHelper = 30;

            setInterval(function() {

                iSecondTransaction--;

                if (iSecondTransaction == 0) {

                    refershTransaction();

                    iSecondTransaction = 5;

                }

                $('#secondTransaction').html(iSecondTransaction);

            }, 1000);

            setInterval(function() {

                iSecondHelper--;

                if (iSecondHelper == 0) {

                    iSecondHelper = 30;

                }

                $('#secondHelper').html(iSecondHelper);

            }, 1000);

        });

        function refershTransaction() {

            $('#tableTransaction').load('/transaction');

        }

        function refreshBankAccount() {
            $('#refreshBankAccount').load('/bank-accounts');
        }
    </script>
@endsection
