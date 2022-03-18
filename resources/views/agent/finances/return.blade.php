@extends('layouts.agent')

@section('css')
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
                        <li class="breadcrumb-item">
                            <a href="" class="text-dark">

                                โอนกลับ</a>
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
                            โอนกลับ
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <form action="{{ route('agent.return.store') }}" method="post" id="formCreateBankAccountRequest"
                        enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>ธนาคารที่โอนกลับ</label>
                                            <div class="radio-list">
                                                @foreach ($bank_accounts as $bank_account_to)
                                                    <label class="radio">
                                                        <input type="radio" value="{{ $bank_account_to->id }}"
                                                            name="bank_account_id" id="bank_account_id">
                                                        <span></span> <img src="{{ asset($bank_account_to->bank->logo) }}"
                                                            alt="" width="25" class="mr-1">
                                                        {{ $bank_account_to->bank->name }}
                                                        {{ $bank_account_to->name }}
                                                        {{ $bank_account_to->account }}
                                                        ($ {{ $bank_account_to->amount }})
                                                        @if ($bank_account_to->type == 1)
                                                            (AUTO)
                                                        @elseif($bank_account_to->type == 2)
                                                            (MANUAL)
                                                        @endif
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class=form-group>
                                            <label for="">ธนาคารของลูกค้า</label>
                                            <select name="bank_id" id="bank_id" class="form-control">
                                                <option value="">เลือก</option>
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->id }}">{{ $bank->code }} |
                                                        {{ $bank->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class=form-group>
                                            <label for="">เลขบัญชีลูกค้า</label>
                                            <input type="tel" class="form-control" name="bank_account" value="XXXXXXXXX">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">จำนวนเงินที่เบิก</label>
                                        <input type="tel" class="form-control" name="amount" input-type="money_decimal" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">หมายเหตุ</label>
                                        <textarea name="remark" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">สลิป</label>
                                        <input type="file" class="form-control" name="slip" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="pull-left">
                                            <p><b>พนักงานที่ทำรายการ: </b>{{ Auth::user()->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="pull-right">
                                    <button class="btn btn-primary">ยืนยัน</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>

    <div class="d-flex flex-column-fluid mt-5">
        <!--begin::Container-fluid-->
        <div class="container-fluid">
            <!--begin::Card-->
            <div class="card card-custom card-shadowless">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">
                            ประวัติการโอนกลับ
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('agent.return') }}" method="get">
                        <div class="row">
                            <div class="col-lg-4">
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
                            <div class="col-lg-4">
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>วันที่/เวลา</th>
                                <th>ธนาคารที่เบิก</th>
                                <th>บชลูกค้า</th>
                                <th>จำนวนเงิน</th>
                                <th>หมายเหตุ</th>
                                <th>พนักงาน</th>
                                {{-- <th>ยกเลิก</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bank_account_returns as $bank_account_return)
                                <tr>
                                    <td>{{ $bank_account_return->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <img src="{{ asset($bank_account_return->bankAccount->bank->logo) }}" alt=""
                                            width="25" class="mr-1">
                                        {{ $bank_account_return->bankAccount->bank->name }}
                                        {{ $bank_account_return->bankAccount->name }}
                                        {{ $bank_account_return->bankAccount->account }}
                                    </td>
                                    <td>{{ $bank_account_return->bank->name }}
                                        {{ $bank_account_return->bank_account }}
                                    </td>
                                    <td align="center">
                                        {{ number_format($bank_account_return->amount, 2) }}
                                    </td>
                                    <td>
                                        {{ $bank_account_return->remark }}
                                    </td>
                                    <td align="center">
                                        {{ $bank_account_return->user->name }}
                                    </td>
                                    {{-- <td width="50">

                                        <button class="btn btn-danger" onclick="deleteFnc({{ $bank_account_return->id }})">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end::Entry-->
@endsection

@section('javascript')
    {!! JsValidator::formRequest('App\Http\Requests\BankAccountWithdrawRequest', '#formCreateBankAccountRequest') !!}

    <script>
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

        function deleteFnc(bank_account_return_id) {

            if (confirm('ยืนยันการลบ ?')) {
                $.post('{{ route('agent.return.delete') }}', {
                    bank_account_return_id: bank_account_return_id
                }, function() {
                    location.reload();
                });
            }
        }
    </script>
@endsection
