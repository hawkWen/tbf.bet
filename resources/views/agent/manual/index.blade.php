@extends('layouts.agent')

@section('css')
@endsection

@section('content')
    <input type="hidden" id="brandId" value="{{ $brand->id }}">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-transparent" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">

            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{ env('APP_NAME') }}</h5>
                    <!--end::Page Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('agent') }}" class="text-muted">ภาพรวม</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-dark">

                                เติมมือ</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <!--begin::Toolbar-->
                <div class="d-flex align-items-center pr-2">
                    <!--begin::Actions-->
                    <a href="#" class="btn btn-primary font-weight-bolder btn-sm " data-toggle="modal"
                        data-target="#createManualModal">
                        <i class="fa fa-plus"></i>เติมมือ</a>
                    <!--end::Actions-->
                </div>
                @if (Auth::user()->user_role_id != 3)
                    <div class="d-flex align-items-center">
                        <!--begin::Actions-->
                        <a href="#" class="btn btn-warning font-weight-bolder btn-sm" data-toggle="modal"
                            data-target="#createCreditFreeModal">
                            <i class="fa fa-plus"></i>เครดิตฟรี</a>
                        <!--end::Actions-->
                    </div>
                @endif
                <!--end::Toolbar-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-fluid-->
        <div class="container-fluid">
            <!--begin::Card-->
            <div class="card card-custom card-shadowless">
                <!--begin::Header-->
                <div class="card-header">

                    <div class="card-title">
                        <div class="tab active">
                            <a href="{{ route('agent.manual') }}">
                                <h3 class="mb-0">Manual</h3>
                            </a>
                        </div>
                        <div class="tab">
                            <a href="{{ route('agent.manual.transaction') }}">
                                <h3 class="mb-0">รายการบอทเติมเงิน</h3>
                            </a>
                        </div>
                        <div class="tab">
                            <a href="{{ route('agent.manual.history') }}">
                                <h3 class="mb-0">ประวัติการทำงาน</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="pull-right">
                        รีเพรชใน <span id="secondRefresh"></span> วิ
                    </div>
                    <span class="switch switch-outline switch-icon switch-success pt-5 pb-2">
                        <label class="pt-2">
                            <input type="checkbox" id="status_brand_bot" value="1"
                                onchange="updateStatusBot({{ $brand->id }})"
                                @if ($brand->status_bot_deposit == 1) checked @endif />
                            <span>
                            </span>เปิด/ปิด บอท เติมเงิน
                        </label>
                    </span>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <button class="btn btn-primary btn-lg btn-block" data-toggle="modal"
                                data-target="#monitorModal">
                                <i class="fa fa-tv"></i> มอนิเตอร์เติมเงิน
                            </button>
                            <hr>
                            <div class="clear-both"></div>
                            <div id="transactionLists"></div>
                            <hr>
                        </div>
                        <div class="col-lg-8">

                            <h3>รายการเติมมือล่าสุด</h3>
                            <hr>
                            <form action="{{ route('agent.manual') }}" method="get">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="">ตั้งแต่วันที่</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control" readonly name="start_date"
                                                value="{{ $dates['input_start_date'] }}" id="kt_datepicker_1" />
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="la la-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-">
                                        <label for="">ถึงวันที่</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control" readonly name="end_date"
                                                value="{{ $dates['input_end_date'] }}" id="kt_datepicker_2" />
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="la la-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <button class="btn btn-primary" style="margin-top: 25px">
                                            <i class="fa fa-search mr-0 pr-0"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>เวลา</th>
                                        <th>ลูกค้า</th>
                                        <th>ธนาคารที่โอน</th>
                                        <th>โปรโมชั่น</th>
                                        <th>จำนวนเงิน</th>
                                        <th>ประเภท</th>
                                        <th>สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_deposits->sortByDesc('updated_at') as $customer_deposit)
                                        <tr>
                                            <td align="center">{{ $customer_deposit->updated_at->format('d/m/Y') }}</td>
                                            <td align="center">{{ $customer_deposit->updated_at->format('H:i') }}</td>
                                            <td>{{ $customer_deposit->name }}
                                                ({{ $customer_deposit->customer->username }})
                                            </td>
                                            <td>
                                                @if (isset($customer_deposit->bankAccount))
                                                    <img src="{{ asset($customer_deposit->bankAccount->bank->logo) }}"
                                                        width="20" class="img-fluid" alt="" width="20">
                                                    {{ $customer_deposit->bankAccount->bank->name }}
                                                    {{ $customer_deposit->bankAccount->account }}
                                                    {{ $customer_deposit->bankAccount->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($customer_deposit->promotion)
                                                    {{ $customer_deposit->promotion->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td align="center">
                                                {{ number_format($customer_deposit->amount, 2) }}
                                                @if ($customer_deposit->bonus > 0)
                                                    + <span class="text-success">{{ $customer_deposit->bonus }}</span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                <span class="text-danger">MANUAL
                                                    @if ($customer_deposit->user)
                                                        ({{ $customer_deposit->user->name }})
                                                    @endif
                                                </span>
                                            </td>
                                            <td align="center">
                                                @if ($customer_deposit->status == 0)
                                                    <span class="text-warning">รอเติมเงิน</span>
                                                @elseif ($customer_deposit->status == 1)
                                                    <span class="text-success">เติมสำเร็จ</span>
                                                @elseif ($customer_deposit->status == 2)
                                                    <span class="text-danger">{{ $input['remark'] }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $customer_deposits->links() }}
                        </div>
                        {{-- <div class="col-lg-4">
                            <h3>ลูกค้าที่ติดสถานะเติมมือ</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td>Username</td>
                                        <td>ชื่อ</td>
                                        <td>ปรับสถานะ</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->username }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>
                                                <a href="{{ route('agent.manual.update', $customer->id) }}"
                                                    class="btn btn-info">
                                                    <i class="fa fa-robot"></i>
                                                    เปลี่ยนเป็นบอทเติม
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
            <!-- Button trigger modal-->

            <!-- Modal-->
            <div class="modal fade" id="createCreditFreeModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('agent.manual.credit-free') }}" method="post" id="formCreditFree">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">เครดิตฟรี</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">ไอดีของลูกค้า</label>
                                        <input type="text" class="form-control" name="username" value="">
                                    </div>
                                    <div class="col-lg-12 mt-4 mb-4">
                                        <label for="">โบนัส</label>
                                        @foreach ($promotions->where('type_promotion', 6) as $promotion)
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="radio" name="promotion_id"
                                                    id="promotion{{ $promotion->id }}" value="{{ $promotion->id }}"
                                                    checked>
                                                <label class="form-check-label" for="promotion{{ $promotion->id }}">
                                                    {{ $promotion->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-lg-12 mt-4">
                                        ผู้ทำรายการ {{ Auth::user()->name }}
                                        <button type="submit" class="btn btn-primary pull-right">
                                            เติมเงิน
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal-->
            <div class="modal fade" id="createManualModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('agent.manual.store') }}" method="post" id="formManual">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Manual</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">ไอดีของลูกค้า</label>
                                        <input type="text" class="form-control" name="username" value="">
                                    </div>
                                    <div class="col-lg-12 ">
                                        <label for="">จำนวนเงิน</label>
                                        <input type="text" class="form-control" name="amount" input-type="money_decimal"
                                            value="" />
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="">ธนาคารที่โอน</label>
                                        <select name="bank_account_id" id="bank_account_id" class="form-control">
                                            <option value="">เลือก</option>
                                            @foreach ($bank_accounts as $bank_account)
                                                @if ($bank_account->bank)
                                                    <option value="{{ $bank_account->id }}">
                                                        {{ $bank_account->bank->name }}
                                                        {{ $bank_account->name }}
                                                        {{ $bank_account->account }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mt-4 mb-4">
                                        <label for="">วันที่โอน</label>
                                        <input type="text" class="form-control" name="transfer_date" id="kt_datepicker_1"
                                            value="{{ date('d/m/Y') }}">
                                    </div>
                                    <div class="col-lg-6 mt-4 mb-4">
                                        <label for="">เวลาที่โอน</label>
                                        <input type="text" class="form-control" name="transfer_time" id="time_1"
                                            value="{{ date('H:i') }}">
                                    </div>
                                    <div class="col-lg-12 mt-4 mb-4">
                                        <label for="">โบนัส</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="promotion_id" id="promotion1"
                                                value="0" checked>
                                            <label class="form-check-label" for="promotion1">
                                                ไม่รับโบนัส
                                            </label>
                                        </div>
                                        @foreach ($promotions as $promotion)
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="radio" name="promotion_id"
                                                    id="promotion{{ $promotion->id }}" value="{{ $promotion->id }}">
                                                <label class="form-check-label" for="promotion{{ $promotion->id }}">
                                                    {{ $promotion->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-lg-12 ">
                                        <label for="">สลิปการโอนเงิน</label>
                                        <input type="file" class="form-control" name="slip" />
                                    </div>
                                    <div class="col-lg-12">
                                    </div>
                                    <div class="col-lg-12 mt-4">
                                        ผู้ทำรายการ {{ Auth::user()->name }}
                                        <button type="submit" class="btn btn-primary pull-right">
                                            เติมเงิน
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
    <!-- Modal-->
    <div class="modal fade" id="monitorModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-ex-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fa fa-tv"></i>
                        มอนิเตอร์การเติมเงิน
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="monitorLists"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    {!! JsValidator::formRequest('App\Http\Requests\DepositManualRequest', '#formManual') !!}

    {{-- {!! JsValidator::formRequest('App\Http\Requests\DepositManualRequest', '#formCreditFree') !!} --}}
    <script>
        // Class definition

        function updateStatus(bank_account_id, type) {

            var status = ($('#' + type + '_' + bank_account_id).is(':checked')) ? 1 : 0;

            $.post('{{ route('agent.bank-account.update-status') }}', {
                bank_account_id: bank_account_id,
                type: type,
                status: status
            }, function() {

            });

        }

        $(function() {

            var seconds = 10;

            setInterval(() => {
                seconds--;
                $('#secondRefresh').html(seconds);
                if (seconds == 0) {
                    seconds = 10;
                }
            }, 1000);

            $('#secondRefresh').html(seconds);

            var brand_id = $('#brandId').val();

            $('#transactionLists').load('/manual/transaction-lists/' + brand_id, function() {

            });
            $('#monitorLists').load('/manual/monitor-lists/' + brand_id, function() {

            });

            setInterval(() => {
                $('#transactionLists').load('/manual/transaction-lists/' + brand_id, function() {

                });
                $('#monitorLists').load('/manual/monitor-lists/' + brand_id, function() {

                });
            }, 10000);



            $("#time_1").click(function() {
                $(this).select();
            });

            $('#time_1').inputmask('99:99');
            // enable clear button 
            $('#kt_datepicker_2, #kt_datepicker_3_validate').datepicker({
                todayBtn: "linked",
                todayHighlight: true,
                format: 'dd/mm/yyyy',
            });
            $('#kt_datepicker_3, #kt_datepicker_3_validate').datepicker({
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
        });

        function updateStatusBot(brand_id) {

            var status = ($('#status_brand_bot').is(':checked')) ? 1 : 0;

            $.post('{{ route('agent.bank-account.update-status-bot') }}', {
                brand_id: brand_id,
                status: status
            }, function() {

            });

        }
    </script>
@endsection
