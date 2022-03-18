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
                <div class="d-flex align-items-center">
                    <!--begin::Actions-->
                    <a href="#" class="btn btn-primary font-weight-bolder btn-sm" data-toggle="modal"
                        data-target="#createCreditFreeModal">
                        <i class="fa fa-plus"></i>สร้างโค้ดเครดิตฟรี</a>
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
                        <h3 class="card-label">
                            รายงานการใช้โค้ดเครดิตฟรี
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="200">วันที่/เวลา</th>
                                <th>ลูกค้าที่ได้รับ</th>
                                <th>โปรโมชั่น</th>
                                <th>โค้ดเครดิตฟรี</th>
                                <th>พนักงานที่สร้างโค้ด</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($credit_free_useds as $credit_free_used)
                                <tr>
                                    <td>{{ $credit_free_used->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $credit_free_used->customer->name }}
                                        ({{ $credit_free_used->customer->username }})
                                    </td>
                                    <td>{{ $credit_free_used->promotion->name }}</td>
                                    <td align="center">{{ $credit_free_used->code }}</td>
                                    <td>
                                        {{ $credit_free_used->user->username }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">
                        {{ $credit_free_useds->links() }}
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
    <!-- Modal-->
    <div class="modal fade" id="createCreditFreeModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('agent.credit-free.generate') }}" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">สร้างโค้ดเครดิตฟรี</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">เลือกโบนัส</label>
                                @foreach ($promotions->where('type_promotion', 6) as $promotion)
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="radio" name="promotion_id"
                                            id="promotion{{ $promotion->id }}" value="{{ $promotion->id }}" checked>
                                        <label class="form-check-label" for="promotion{{ $promotion->id }}">
                                            {{ $promotion->name }}
                                        </label>
                                    </div>
                                @endforeach
                                <span class="badge badge-warning">กรุณาเพิ่มเครดิตฟรีที่หน้าจัดการโปรโมชั่นก่อน</span>
                            </div>
                            <div class="col-lg-4 ml-auto">
                                <label for="">จำนวนโค้ดที่สร้าง</label>
                                <input type="number" name="number" value="10" class="form-control" max="100"
                                    input-type="money" />
                            </div>
                            <div class="col-lg-12 mt-4">
                                ผู้ทำรายการ {{ Auth::user()->name }} ({{ Auth::user()->username }})
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-warning font-weight-bold"
                            data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">สร้างเลย</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
