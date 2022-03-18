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
                    <form action="{{ route('agent.report.customer') }}" method="get">
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
                            <div class="col-lg-2 form-check mx-4" style="margin-top: 32px">
                                <input type="checkbox" class="form-check-input" id="highest-deposit" name="filter_deposit">
                                <label class="form-check-label mx-2" for="highest-deposit">
                                    เรียงตามยอดเติมสูงสุด
                                </label>
                            </div>
                            {{-- <div class="col-lg-2 form-check" style="margin-top: 30px">
                                <input type="radio" class="form-check-input" id="highest-withdraw" name="filter_deposit" value="little">
                                <label class="form-check-label mx-2" for="highest-withdraw">
                                    ยอดถอนสูงสุด
                                </label>
                            </div> --}}


                            <div class="col-lg-1">
                                <button class="btn btn-primary" style="margin-top: 25px">
                                    <i class="fa fa-search mr-0 pr-0"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    {{-- <form action="{{ route('agent.report.customer') }}">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="">ค้นหาจากชื่อลูกค้า</label>
                                <input type="text" class="form-control" name="name" value="">
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-primary" style="margin-top: 25px">
                                    <i class="fa fa-search mr-0 pr-0"></i>
                                </button>
                            </div>
                        </div>
                    </form> --}}
                    <hr>
                    @if (Auth::user()->user_role_id == 4)
                        <form action="{{ route('agent.report.customer-excel') }}" method="get">
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
                    @endif
                    <table class="table table-bordered">
                        @if ($filter_deposit == 'on')
                            <thead>
                                <tr>
                                    <th width="50">วันที่สมัคร</th>
                                    <th>โปรไฟล์</th>
                                    <th>ชื่อลูกค้า</th>
                                    <th>ข้อมูลธนาคาร</th>
                                    <th>ไอดีในเกมส์</th>
                                    <th>ยอดเติม</th>
                                    @if (Auth::user()->user_role_id == 4)
                                        <th>เบอร์โทรศัพท์</th>
                                        <th>ไลน์ไอดี</th>
                                    @endif
                                    <th>ช่องทางการรู้จัก</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customer_deposits as $customer)
                                    <tr>
                                        <td width="50">{{ $customer->customer->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td align="center" width="50">
                                            @if ($customer->customer->img_url == '')
                                                <img src="https://via.placeholder.com/50" class="img-fluid img-circle"
                                                    alt="">
                                            @else
                                                <img src="{{ $customer->customer->img_url }}"
                                                    class="img-fluid img-circle" width="50" alt="">
                                            @endif
                                        </td>
                                        <td>
                                            {{ $customer->customer->name }}
                                        </td>
                                        <td>
                                            <img src="{{ asset($customer->customer->bank->logo) }}" width="40"
                                                class="img-fluid pr-3" alt="">
                                            {{ $customer->customer->bank->name }}
                                            {{ $customer->customer->bank_account }}
                                        </td>
                                        <td>
                                            {{ $customer->customer->username }}
                                        </td>
                                        <td align="center">
                                            <span class="text-success">
                                                {{ $customer->deposit_amount_total }}
                                            </span>
                                        </td>
                                        @if (Auth::user()->user_role_id == 4)
                                            <td>
                                                {{ $customer->customer->telephone }}
                                            </td>
                                            <td>
                                                {{ $customer->customer->line_id }}
                                            </td>
                                        @endif
                                        <td>
                                            <p>{{ $customer->customer->from_type }}</p>
                                            <small>{{ $customer->customer->from_type_remark }}</small>
                                        </td>
                                        <td width="200">
                                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                                data-target="#updateCustomer_{{ $customer->customer->id }}">
                                                <i class="fa fa-edit pr-0"></i> แก้ไขข้อมูลบัญชีธนาคาร
                                            </button>
                                            <div class="modal fade" id="updateCustomer_{{ $customer->id }}" modal
                                                data-backdrop="false" tabindex="-1">
                                                <div class="modal-dialog text-dark" role="document"
                                                    style="margin-top: 70px;">
                                                    <div class="modal-content bg-warning">
                                                        <form action="{{ route('agent.report.customer-update') }}"
                                                            method="post">
                                                            <input type="hidden" name="customer_id"
                                                                value="{{ $customer->customer->id }}">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title text-dark" id="exampleModalLabel">
                                                                    แก้ไขข้อมูลบัญชีธนาคาร</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <label for="">ธนาคาร</label>
                                                                        <select name="bank_id" id="bank_id"
                                                                            class="form-control">
                                                                            <option value="">เลือก</option>
                                                                            @foreach ($banks as $bank)
                                                                                <option
                                                                                    value="{{ $bank->id }}:{{ $bank->code }}">
                                                                                    {{ $bank->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <label for="">เลขที่บัญชีธนาคาร</label>
                                                                        <input type="text" class="form-control"
                                                                            name="bank_account">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="pull-right">
                                                                            <button type="submit" class="btn btn-primary">
                                                                                <i class="fa fa-check"></i>
                                                                                ยืนยัน
                                                                            </button>
                                                                            <button type="button" class="btn btn-danger"
                                                                                data-dismiss="modal">
                                                                                <i class="fa fa-times"></i>
                                                                                ยกเลิก
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- agent.report.customer-password --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <thead>
                                <tr>
                                    <th width="50">วันที่สมัคร</th>
                                    <th>โปรไฟล์</th>
                                    <th>ชื่อลูกค้า</th>
                                    <th>ข้อมูลธนาคาร</th>
                                    <th>ไอดีในเกมส์</th>
                                    <th>ยอดเติม/ถอน</th>
                                    @if (Auth::user()->user_role_id == 4)
                                        <th>เบอร์โทรศัพท์</th>
                                        <th>ไลน์ไอดี</th>
                                    @endif
                                    <th>ช่องทางการรู้จัก</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td width="50">{{ $customer->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td align="center" width="50">
                                            @if ($customer->img_url == '')
                                                <img src="https://via.placeholder.com/50" class="img-fluid img-circle"
                                                    alt="">
                                            @else
                                                <img src="{{ $customer->img_url }}" class="img-fluid img-circle"
                                                    width="50" alt="">
                                            @endif
                                        </td>
                                        <td>
                                            {{ $customer->name }}
                                        </td>
                                        <td>
                                            <img src="{{ asset($customer->bank->logo) }}" width="40"
                                                class="img-fluid pr-3" alt="">
                                            {{ $customer->bank->name }} {{ $customer->bank_account }}
                                        </td>
                                        <td>
                                            {{ $customer->username }}
                                        </td>
                                        <td align="center">
                                            <span class="text-success">
                                                {{ $customer->deposits->sum('amount') }}
                                            </span> /
                                            <span class="text-danger">
                                                {{ $customer->withdraws->sum('amount') }}
                                            </span>
                                        </td>
                                        @if (Auth::user()->user_role_id == 4)
                                            <td>
                                                {{ $customer->telephone }}
                                            </td>
                                            <td>
                                                {{ $customer->line_id }}
                                            </td>
                                        @endif
                                        <td>
                                            <p>{{ $customer->from_type }}</p>
                                            <small>{{ $customer->from_type_remark }}</small>
                                        </td>
                                        <td width="200">
                                            {{-- <button type="button" class="btn btn-warning" onclick="resetPassword({{$customer->id}})">
                                <i class="fa fa-edit pr-0"></i> แก้ไขรหัสผ่าน
                            </button> --}}
                                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                                data-target="#updateCustomer_{{ $customer->id }}">
                                                <i class="fa fa-edit pr-0"></i> แก้ไขข้อมูลบัญชีธนาคาร
                                            </button>
                                            <div class="modal fade" id="updateCustomer_{{ $customer->id }}" modal
                                                data-backdrop="false" tabindex="-1">
                                                <div class="modal-dialog text-dark" role="document"
                                                    style="margin-top: 70px;">
                                                    <div class="modal-content bg-warning">
                                                        <form action="{{ route('agent.report.customer-update') }}"
                                                            method="post">
                                                            <input type="hidden" name="customer_id"
                                                                value="{{ $customer->id }}">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title text-dark" id="exampleModalLabel">
                                                                    แก้ไขข้อมูลบัญชีธนาคาร</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <label for="">ธนาคาร</label>
                                                                        <select name="bank_id" id="bank_id"
                                                                            class="form-control">
                                                                            <option value="">เลือก</option>
                                                                            @foreach ($banks as $bank)
                                                                                <option
                                                                                    value="{{ $bank->id }}:{{ $bank->code }}">
                                                                                    {{ $bank->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <label for="">เลขที่บัญชีธนาคาร</label>
                                                                        <input type="text" class="form-control"
                                                                            name="bank_account">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="pull-right">
                                                                            <button type="submit" class="btn btn-primary">
                                                                                <i class="fa fa-check"></i>
                                                                                ยืนยัน
                                                                            </button>
                                                                            <button type="button" class="btn btn-danger"
                                                                                data-dismiss="modal">
                                                                                <i class="fa fa-times"></i>
                                                                                ยกเลิก
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- agent.report.customer-password --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                    @if ($filter_deposit == null)
                        <div class="pull-right">
                            {{ $customers->links() }}
                        </div>
                    @endif
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

        function resetPassword(customer_id) {

            $.post('{{ route('agent.report.customer-password') }}', {
                customer_id: customer_id
            }, function(r) {
                location.reload();
            });

        }
    </script>
@endsection
