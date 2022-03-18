@extends('layouts.agent')

@section('css')
    <style>
        .table th,
        .table td {
            font-size: 18px !important;
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
                        <li class="breadcrumb-item">
                            <a href="" class="text-dark">

                                สรุปรายได้</a>
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
                            รายงานสรุปรายได้ ตั้งแต่วันที่ {{ $dates['input_start_date'] }} -
                            {{ $dates['input_end_date'] }}
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <form action="{{ route('agent.report.summary') }}" method="get">
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
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>รายการ</th>
                                        <th align="center" colspan="2">จำนวนเงิน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td align="left">ยอดเติมเงิน</td>
                                        <td align="center" class="text-success" colspan="2"> +
                                            {{ number_format($customer_deposits->sum('amount'), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left">ยอดถอนเงิน</td>
                                        <td align="center" class="text-danger" colspan="2"> -
                                            {{ number_format($customer_withdraws->sum('amount'), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left">รายจ่ายโปรโมชั่น</td>
                                        <td align="center" class="text-warning" colspan="2"> -
                                            {{ number_format($promotion_costs->sum('bonus'), 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left">เบิกจ่าย</td>
                                        <td align="center" class="text-warning" colspan="2"> -
                                            {{ number_format($bank_account_withdraws->sum('amount'), 2) }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <td align="left">โอนกลับลูกค้า</td>
                                        <td align="center" class="text-warning" colspan="2"> -
                                            {{ number_format($bank_account_returns->sum('amount'), 2) }}</td>
                                    </tr> --}}
                                    {{-- @if ($brand->game_id == 5)
                                    <tr>
                                        <td align="left">
                                            เครดิตคงค้าง<div class="pull-right">
                                            </div>
                                            <br>
                                            <small>อัพเดทล่าสุด {{$brand->last_update_credit_remain}}</small>
                                        </td>
                                        <td align="center" >{{number_format($brand->credit_remain,2)}}</td>
                                        <td width="100" align="center">
                                            <button class="btn btn-info btn-sm" onclick="updateCreditNormal({{$brand->id}})">
                                                <i class="fa fa-sync"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td align="left">
                                            เครดิตคงค้าง <div class="pull-right">
                                                
                                                จำนวนไอดี {{$customers->count()}}
                                            </div>
                                            <br>
                                            <small>อัพเดทล่าสุด {{$customers->first()->last_update_credit}}</small>
                                        </td>
                                        <td align="center" class="text-warning">
                                            {{number_format($customers->sum('credit'),2)}}
                                        </td>
                                        <td width="100" align="center">
                                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#updateCreditModal">

                                                <i class="fa fa-sync"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif --}}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td align="right">รวม</td>
                                        <td align="center" colspan="2">
                                            @if ($total > 0)
                                                <span class="text-success">
                                                    {{ number_format($total, 2) }}
                                                </span>
                                            @else
                                                <span class="text-danger">
                                                    {{ number_format($total, 2) }}
                                                </span>
                                            @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
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

        function updateCustomers(brand_id) {

            var customers = JSON.parse($('#users').val());

            $.each(customers, function(k, v) {
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                console.log(percentComplete);
                            }
                        }, false);
                        //Download progress
                        xhr.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                console.log(percentComplete);
                            }
                        }, false);
                        return xhr;
                    },
                    type: 'POST',
                    url: '{{ route('agent.report.summary-credit') }}',
                    data: {
                        brand_id: brand_id,
                        customer_id: v.id,
                    },
                    success: function(data) {
                        $('#creditCounted').html(data.count);
                        console.log(data);
                    }
                });
            });

        }

        function updateCreditNormal(brand_id) {

            $.post('{{ route('agent.report.summary-credit') }}', {
                brand_id: brand_id
            }, function(r) {
                location.reload();
            });

        }
    </script>
@endsection
