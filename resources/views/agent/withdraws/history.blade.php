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
                            <a href="" class="text-dark">หน้าจอการถอนเงิน</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
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
                            หน้าจอการถอนเงิน
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('agent.withdraw') }}" class="btn btn-secondary"> <i class="fa fa-credit-card"></i>
                        ถอนเงิน</a>
                    <a href="{{ route('agent.withdraw.history') }}" class="btn btn-danger"> <i class="fa fa-history"></i>
                        ประวัติการถอนเงิน</a>
                    <hr>
                    <form action="{{ route('agent.withdraw.history') }}" method="get">
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
                </div>
                <!--begin::Body-->

                <div class="card-body">
                    <div class="pull-right">
                        <form action="{{ route('agent.withdraw.export') }}" method="get">
                            <input type="hidden" class="form-control" name="start_date"
                                value="{{ $dates['input_start_date'] }}">
                            <input type="hidden" class="form-control" name="end_Date"
                                value="{{ $dates['input_end_date'] }}">
                            <div class="col-lg-2">
                                <button class="btn btn-primary" style="margin-top: 25px" data-toggle="tooltip"
                                    title="Export Excel">
                                    <i class="fa fa-file-export mr-0 pr-0"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>เวลา</th>
                                <th>ลูกค้า</th>
                                <th>ธนาคารที่โอน</th>
                                <th>จำนวนเงิน</th>
                                <th>ประเภท</th>
                                <th>สถานะ</th>
                                <th>remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customer_withdraws->sortByDesc('updated_at') as $customer_withdraw)
                                <tr>
                                    <td align="center">{{ $customer_withdraw->updated_at->format('d/m/Y') }}</td>
                                    <td align="center">{{ $customer_withdraw->updated_at->format('H:i') }}</td>
                                    <td>{{ $customer_withdraw->name }} ({{ $customer_withdraw->customer->username }})
                                    </td>
                                    <td>
                                        @if (isset($customer_withdraw->bankAccount))
                                            <img src="{{ asset($customer_withdraw->bankAccount->bank->logo) }}"
                                                width="20" class="img-fluid" alt="" width="20">
                                            {{ $customer_withdraw->bankAccount->bank->name }}
                                            {{ $customer_withdraw->bankAccount->account }}
                                            {{ $customer_withdraw->bankAccount->name }}
                                        @endif
                                    </td>
                                    <td align="center">
                                        {{ number_format($customer_withdraw->amount, 2) }}
                                    </td>
                                    <td align="center">
                                        @if ($customer_withdraw->type_withdraw == 1)
                                            <span class="text-success">BOT</span>
                                        @else
                                            <span class="text-danger">MANUAL
                                                @if ($customer_withdraw->user)
                                                    ({{ $customer_withdraw->user->name }})
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if ($customer_withdraw->status == 0)
                                            <span class="text-warning">รอพนักงานอนุมัติ</span>
                                        @elseif($customer_withdraw->status == 1)
                                            <span class="">พนักงานถอน</span>
                                        @elseif($customer_withdraw->status == 2)
                                            <span class="text-success">ถอนเรียบร้อย</span>
                                        @elseif($customer_withdraw->status == 3)
                                            <span class="text-warning">บอทปิดทำงาน</span>
                                        @elseif($customer_withdraw->status == 4)
                                            <span class="text-danger">API ERROR</span>
                                        @elseif($customer_withdraw->status == 5)
                                            <span class="text-danger">ยกเลิก</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $customer_withdraw->remark }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">
                        {{ $customer_withdraws->links() }}
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
    {!! JsValidator::formRequest('App\Http\Requests\DepositHoldRequest', '#formDepositHold') !!}

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
