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
                <div class="d-flex align-items-center">
                    <!--begin::Actions-->
                    <a href="#" class="btn btn-primary font-weight-bolder btn-sm" data-toggle="modal"
                        data-target="#createManualModal">
                        <i class="fa fa-plus"></i>เติมมือ</a>
                    <!--end::Actions-->
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
                        <div class="tab">
                            <a href="{{ route('agent.manual') }}">
                                <h3 class="mb-0">Manual</h3>
                            </a>
                        </div>
                        <div class="tab">
                            <a href="{{ route('agent.manual.transaction') }}">
                                <h3 class="mb-0">รายการบอทเติมเงิน</h3>
                            </a>
                        </div>
                        <div class="tab active">
                            <a href="{{ route('agent.manual.history') }}">
                                <h3 class="mb-0">ประวัติการทำงาน</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
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
                                    <td>{{ $customer_deposit->name }} ({{ $customer_deposit->customer->username }})
                                    </td>
                                    <td>
                                        @if (isset($customer_deposit->bankAccount))
                                            <img src="{{ asset($customer_deposit->bankAccount->bank->logo) }}" width="20"
                                                class="img-fluid" alt="" width="20">
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
    {!! JsValidator::formRequest('App\Http\Requests\DepositManualRequest', '#formManual') !!}
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
