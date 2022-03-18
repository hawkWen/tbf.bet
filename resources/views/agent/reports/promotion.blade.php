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
                            รายงานลูกค้า ตั้งแต่วันที่ {{ $dates['input_start_date'] }} - {{ $dates['input_end_date'] }}
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <form action="{{ route('agent.report.promotion') }}" method="get">
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
                    <hr>
                    <form action="{{ route('agent.report.promotion-excel') }}" method="get">
                        <input type="hidden" class="form-control" name="start_date"
                            value="{{ $dates['input_start_date'] }}">
                        <input type="hidden" class="form-control" name="end_Date"
                            value="{{ $dates['input_end_date'] }}">
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
                                <th>ชื่อลูกค้า</th>
                                <th>โปรโมชั่น</th>
                                <th>ยอดเติมเงิน</th>
                                <th>จำนวนโบนัส</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($promotion_costs as $promotion_cost)
                                <tr>
                                    <td>{{ $promotion_cost->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $promotion_cost->customer->name }}
                                        ({{ $promotion_cost->customer->username }})
                                    </td>
                                    <td>
                                        @if ($promotion_cost->promotion)
                                            {{ $promotion_cost->promotion->name }}
                                        @else
                                            โบนัสวงล้อ
                                        @endif
                                    </td>
                                    <td align="right">{{ number_format($promotion_cost->amount, 2) }}</td>
                                    <td align="right">{{ number_format($promotion_cost->bonus, 2) }}</td>
                                    <td>
                                        @if ($promotion_cost->status == 0)
                                            <span class="text-warning">กำลังใช้งาน</span>
                                        @elseif($promotion_cost->status == 1)
                                            <span class="text-success">ใช้โปรหมด</span>
                                        @elseif($promotion_cost->status == 2)
                                            <span class="text-success">ดึงโปรคืน</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">
                        {{ $promotion_costs->links() }}
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
