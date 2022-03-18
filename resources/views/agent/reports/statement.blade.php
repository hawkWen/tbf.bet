@extends('layouts.agent')

@section('css')
    <style>
        .table th,
        .table td {
            font-size: 12px !important;
        }

    </style>
@endsection

@section('content')
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
                <div class="d-flex align-items-center">
                </div>
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
                        <h3 class="card-label">
                            รายงานบัญชีธนาคาร ตั้งแต่วันที่ {{ $dates['input_start_date'] }} -
                            {{ $dates['input_end_date'] }}
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <form action="{{ route('agent.report.statement') }}" method="get">
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
                            <div class="col-lg-3">
                                <label for="">เลือกบัญชี</label>
                                <select class="form-control" readonly name="bank_account_id"
                                    value="{{ $dates['input_end_date'] }}">
                                    <option value="">เลือก</option>
                                    @foreach ($bank_accounts as $bank_account)
                                        <option value="{{ $bank_account->id }}"
                                            @if ($bank_account_select == $bank_account->id) selected @endif>
                                            {{ $bank_account->bank->name }} {{ $bank_account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-primary" style="margin-top: 25px">
                                    <i class="fa fa-search mr-0 pr-0"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <form action="{{ route('agent.report.statement-excel') }}" method="get">
                        <input type="hidden" class="form-control" name="start_date"
                            value="{{ $dates['input_start_date'] }}">
                        <input type="hidden" class="form-control" name="end_Date" value="{{ $dates['input_end_date'] }}">
                        <div class="pull-right">
                            <button class="btn btn-primary" style="margin-bottom: 20px" data-toggle="tooltip"
                                title="Export Excel">
                                <i class="fa fa-file-export mr-0 pr-0"></i>
                            </button>
                        </div>
                    </form>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="200">วันที่/เวลา</th>
                                <th>ธนาคาร</th>
                                <th>ประเภท</th>
                                <th>จำนวนเงิน</th>
                                <th>พนักงานที่ทำรายการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bank_account_histories as $bank_account_history)
                                <tr>
                                    <td>{{ $bank_account_history->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <img src="{{ asset($bank_account_history->bankAccount->bank->logo) }}" width="20"
                                            class="img-fluid" alt="" width="20">
                                        {{ $bank_account_history->bankAccount->bank->name }}
                                        {{ $bank_account_history->bankAccount->name }}
                                    </td>
                                    <td>
                                        @if ($bank_account_history->table == 'customer_deposits')
                                            ลูกค้าเติมเงิน
                                        @elseif($bank_account_history->table == 'customer_withdraws')
                                            ลูกค้าถอนเงิน
                                        @elseif($bank_account_history->table == 'bank_account_withdraws')
                                            เบิกจ่าย
                                        @elseif($bank_account_history->table == 'bank_account_returns')
                                            โอนคืนลูกค้า
                                        @elseif($bank_account_history->table == 'bank_account_transfers')
                                            โยกเงินเข้า / ออก
                                        @elseif($bank_account_history->table == 'bank_account_receives')
                                            รับเงินจากสายบน
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if ($bank_account_history->type == 1)
                                            <span class="text-success"> +
                                                {{ number_format($bank_account_history->amount, 2) }}</span>
                                        @else
                                            <span class="text-danger"> -
                                                {{ number_format($bank_account_history->amount, 2) }}</span>
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if ($bank_account_history->user)
                                            {{ $bank_account_history->user->name }}
                                        @else
                                            BOT
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">
                        {{ $bank_account_histories->links() }}
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
            <!-- Button trigger modal-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
@endsection

@section('javascript')
    {!! JsValidator::formRequest('App\Http\Requests\PromotionRequest', '#formCreateDeposit') !!}

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
        });
    </script>
@endsection
