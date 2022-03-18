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
                            <a href="" class="text-dark">หน้าจอการเติมเงิน</a>
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
                            หน้าจอการเติมเงิน
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('agent.deposit') }}" class="btn btn-secondary"> <i
                            class="fa fa-hand-holding-usd"></i>
                        เติมเงิน</a>
                    <a href="{{ route('agent.deposit.history') }}" class="btn btn-primary"> <i class="fa fa-history"></i>
                        ประวัติการเติมเงิน</a>
                    <hr>
                    <form action="{{ route('agent.deposit.history') }}" method="get">
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
                        <form action="{{ route('agent.deposit.export') }}" method="get">
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
                                <th>โปรโมชั่น</th>
                                <th>จำนวนเงิน</th>
                                <th>ประเภท</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customer_deposits->sortByDesc('updated_at') as $customer_deposit)
                                @if (isset($customer_deposit->customer))
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
                                            @if ($customer_deposit->type_manual == 1)
                                                <span class="text-danger">BOT
                                                    @if ($customer_deposit->user)
                                                        ({{ $customer_deposit->user->name }})
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-success">MANUAL</span>
                                            @endif
                                        </td>
                                        <td align="center">
                                            @if ($customer_deposit->status == 0)
                                                <span class="text-warning">รอถอนเงิน</span>
                                            @elseif ($customer_deposit->status == 1)
                                                <span class="text-success">เติมสำเร็จ</span>
                                            @elseif ($customer_deposit->status == 2)
                                                <span class="text-danger">{{ $customer_deposit->remark }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">
                        {{ $customer_deposits->links() }}
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
