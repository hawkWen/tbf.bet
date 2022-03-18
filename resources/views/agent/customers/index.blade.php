@php
use App\Helpers\Helper;
@endphp
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
                        <li class="breadcrumb-item">
                            <a href="" class="text-dark">

                                จัดการลูกค้า</a>
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
                        <div class="tab active">
                            <a href="{{ route('agent.customer') }}">
                                <h3 class="mb-0">จัดการลูกค้า</h3>
                            </a>
                        </div>
                        <div class="tab">
                            <a href="{{ route('agent.invite') }}">
                                <h3 class="mb-0">ระบบแนะนำเพื่อน</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <form action="{{ route('agent.customer') }}">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="">ค้นหาจากชื่อลูกค้า ไอดีในเกมส์ บัญชีธนาคาร เบอร์โทรศัพท์</label>
                                <input type="text" class="form-control" name="name" value="{{ $search }}">
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-primary" style="margin-top: 25px">
                                    <i class="fa fa-search mr-0 pr-0"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('agent.customer') }}">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="">ค้นหาเลขที่บัญชีที่ซ้ำกัน</label>
                                <input type="text" class="form-control" name="bank_account" value="{{ $search }}">
                                <span class="badge badge-info">เลขบัญชีไทยพาณิชย์ 4 หลักสุดท้าย , ธนาคารกสิกรไทย 6
                                    หลักสุดท้าย</span>
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
                                <th width="50">วันที่สมัคร</th>
                                <th>ชื่อลูกค้า</th>
                                <th>ข้อมูลธนาคาร</th>
                                <th>ไอดีในเกมส์</th>
                                <th>ยอดเติม/ถอน</th>
                                <th>เครดิตปัจจุบัน</th>
                                @if (Auth::user()->user_role_id != 3)
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>ไลน์ไอดี</th>
                                @endif
                                <th>ช่องทางการรู้จัก</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers->where('brand_id', '=', Auth::user()->brand_id) as $customer)
                                <tr @if ($customer->type == 1) class="bg-warning" @endif>
                                    <td align="center" width="50">
                                        @if ($customer->created_at)
                                            {{ $customer->created_at->format('d/m/Y') }}
                                            <br>
                                            {{ $customer->created_at->format('H:i:s') }}
                                        @endif
                                    </td>
                                    <td>
                                        <p>
                                            {{ $customer->name }}
                                        </p>
                                        <a href="{{ route('agent.customer.show', $customer->id) }}">ประวัติการเติมถอน</a>
                                    </td>
                                    <td>
                                        <img src="{{ asset($customer->bank->logo) }}" width="40" class="img-fluid pr-3"
                                            alt="">
                                        {{ $customer->bank->code }}
                                        <p>
                                            {{ $customer->bank_account }}
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            {{ $customer->username }}
                                        </p>
                                        <p>
                                            @if ($customer->type == 1)
                                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                                ลูกค้าติด blacklist
                                            @endif
                                        </p>
                                    </td>
                                    <td align="center">
                                        <span class="text-success">
                                            {{ number_format($customer->deposits->sum('amount'), 2) }}
                                        </span> /
                                        <span class="text-danger">
                                            {{ number_format($customer->withdraws->sum('amount'), 2) }}
                                        </span>
                                        @if ($customer->bet)
                                            <p class="text-info">
                                                {{ $customer->bet->turn_over }}
                                            </p>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <p id="customerCredit_{{ $customer->id }}">
                                            {{ number_format($customer->credit, 2) }}</p>
                                        <p>อัพเดทล่าสุด</p>
                                        <p>{{ $customer->last_update_credit }}</p>
                                        <button class="btn btn-default btn-sm" type="button"
                                            onclick="updateCredit({{ $customer->id }})">
                                            <i class="fa fa-sync"></i>
                                        </button>
                                    </td>
                                    @if (Auth::user()->user_role_id != 3)
                                        <td>
                                            {{ $customer->telephone }}
                                        </td>
                                        <td>
                                            {{ $customer->line_id }}
                                        </td>
                                    @endif
                                    <td>
                                        <small>{{ $customer->from_type_remark }}</small>
                                        @if ($customer->username != '')
                                            <p>{{ $customer->from_type }}</p>
                                        @endif
                                    </td>
                                    <td width="265">
                                        @if (Auth::user()->role_id != 4)
                                            <button type="button" class="btn btn-warning btn-xs" data-toggle="modal"
                                                data-target="#changePassword_{{ $customer->id }}" data-toggle="tooltip"
                                                data-placement="top" title="แก้ไขรหัสผ่าน">
                                                <span data-toggle="tooltip" data-placement="top" title="แก้ไขรหัสผ่าน">
                                                    <i class="fa fa-key"></i>

                                                </span>
                                            </button>
                                            <button type="button" class="btn btn-info btn-xs" data-toggle="modal"
                                                data-target="#updateCustomer_{{ $customer->id }}">
                                                <span data-toggle="tooltip" data-placement="top" title="แก้ไขรหัสผ่าน">
                                                    <i class="fa fa-credit-card"></i>
                                                </span>
                                            </button>
                                            <button class="btn btn-secondary btn-xs"
                                                data-target="#managePromotionModal_{{ $customer->id }}"
                                                data-toggle="modal">
                                                <span data-toggle="tooltip" data-placement="top" title="จัดการโปรโมชั่น">
                                                    <i class="fa fa-edit"></i>
                                                </span>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                                                data-target="#minusCreditModal_{{ $customer->id }}">
                                                <span data-toggle="tooltip" data-placement="top" title="โบนัสพิเศษ">
                                                    <i class="fa fa-minus"></i>
                                                </span>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"
                                                data-target="#promotionCustomer_{{ $customer->id }}">
                                                <span data-toggle="tooltip" data-placement="top" title="โบนัสพิเศษ">
                                                    <i class="fa fa-gift"></i>
                                                </span>
                                            </button>
                                        @endif
                                        <div class="modal fade" id="changePassword_{{ $customer->id }}" modal
                                            data-backdrop="false" tabindex="-1">
                                            <div class="modal-dialog text-dark" role="document" style="margin-top: 70px;">
                                                <div class="modal-content ">
                                                    <form action="{{ route('agent.customer.change-password') }}"
                                                        method="post">
                                                        <input type="hidden" name="customer_id"
                                                            value="{{ $customer->id }}">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                อัพเดทไอดี และ รหัสผ่าน
                                                            </h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="">ไอดีในเกมส์</label>
                                                                    <input type="text" class="form-control"
                                                                        name="username" value="{{ $customer->username }}"
                                                                        required readonly>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="">รหัสผ่านใหม่</label>
                                                                    <input type="text" class="form-control"
                                                                        name="password" minlength="6">

                                                                    @if (Auth::user()->user_role_id != 3)
                                                                        <small class="form-text text-muted">
                                                                            รหัสผ่านเดิม:
                                                                            {{ $customer->password_generate }}
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="">Blacklist</label>
                                                                    <br>
                                                                    <input data-switch="true" type="checkbox" name="type"
                                                                        id="status_type_{{ $customer->id }}"
                                                                        @if ($customer->type == 1) checked @endif />
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
                                        <div class="modal fade" id="minusCreditModal_{{ $customer->id }}" modal
                                            data-backdrop="false" tabindex="-1">
                                            <div class="modal-dialog text-dark" role="document" style="margin-top: 70px;">
                                                <div class="modal-content ">
                                                    <form action="{{ route('agent.customer.minus-credit') }}"
                                                        method="post">
                                                        <input type="hidden" name="customer_id"
                                                            value="{{ $customer->id }}">
                                                        <div class="modal-header bg-danger">
                                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                ดึงเครดิตคืน
                                                            </h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="">เครดิต</label>
                                                                    <input type="text" class="form-control" name="amount"
                                                                        input-type="money_decimal" value="0.00">
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
                                        <div class="modal fade" id="updateCustomer_{{ $customer->id }}" modal
                                            data-backdrop="false" tabindex="-1">
                                            <div class="modal-dialog text-dark" role="document" style="margin-top: 70px;">
                                                <div class="modal-content ">
                                                    <form action="{{ route('agent.customer.update') }}" method="post">
                                                        <input type="hidden" name="customer_id"
                                                            value="{{ $customer->id }}">
                                                        <div class="modal-header bg-info">
                                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                แก้ไขข้อมูลบัญชีธนาคาร</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="">ชื่อลูกค้า</label>
                                                                    <input type="name" class="form-control" name="name"
                                                                        value="{{ $customer->name }}">
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="">ธนาคาร</label>
                                                                    <select name="bank_id" id="bank_id"
                                                                        class="form-control" required>
                                                                        <option value="">เลือก</option>
                                                                        @foreach ($banks as $bank)
                                                                            <option
                                                                                value="{{ $bank->id }}:{{ $bank->code }}"
                                                                                @if ($bank->id == $customer->bank_id) selected @endif>
                                                                                {{ $bank->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="">เลขที่บัญชีธนาคาร</label>
                                                                    <input type="text" class="form-control"
                                                                        name="bank_account"
                                                                        value="{{ $customer->bank_account }}" required>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="">เบอร์โทรศัพท์</label>
                                                                    <input type="tel" class="form-control"
                                                                        name="telephone"
                                                                        value="{{ $customer->telephone }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="pull-left">
                                                                        <p><b>พนักงานที่ทำรายการ:
                                                                            </b>{{ Auth::user()->name }}</p>
                                                                    </div>
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
                                        <div class="modal fade" id="managePromotionModal_{{ $customer->id }}" modal
                                            data-backdrop="false" tabindex="-1">
                                            <div class="modal-dialog modal-lg text-dark" role="document"
                                                style="margin-top: 70px;">
                                                <div class="modal-content">
                                                    <form>
                                                        <input type="hidden" name="customer_id"
                                                            value="{{ $customer->id }}">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                จัดการโปรโมชั่น</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <td>วันที่รับโปรโมชั่น</td>
                                                                            <td>โปรโมชั่น</td>
                                                                            <td>จำนวนเงิน</td>
                                                                            <td>โบนัส</td>
                                                                            <td>จัดการ</td>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($customer->promotionCosts->where('status', '=', 0) as $promotion_cost)
                                                                            <tr>
                                                                                <td>{{ $promotion_cost->created_at->format('d/m/Y H:i:s') }}
                                                                                </td>
                                                                                <td>
                                                                                    @if ($promotion_cost->promotion)
                                                                                        <a onclick="alert('{{ $promotion_cost->promotion->name }}')"
                                                                                            style="cursor: pointer">
                                                                                            <div class="cut-text">
                                                                                                {{ $promotion_cost->promotion->name }}
                                                                                            </div>
                                                                                        </a>
                                                                                    @else
                                                                                        โบนัสวงล้ิ
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ number_format($promotion_cost->amount, 2) }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ number_format($promotion_cost->bonus, 2) }}
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-warning"
                                                                                        onclick="clearPromotion({{ $customer->id }},{{ $promotion_cost->id }})">
                                                                                        เคลียโปรโมชั่น
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="promotionCustomer_{{ $customer->id }}" modal
                                            data-backdrop="false" tabindex="-1">
                                            <div class="modal-dialog text-dark" role="document" style="margin-top: 70px;">
                                                <div class="modal-content ">
                                                    <form id="form_promotion_{{ $customer->id }}">
                                                        <input type="hidden" name="customer_id"
                                                            value="{{ $customer->id }}">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                เพิ่มโปรโมชั่นพิเศษ</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="">เลือกโปรโมชั่นที่ให้</label>
                                                                    <select name="promotion_id"
                                                                        id="promotion_id_{{ $customer->id }}"
                                                                        onchange="changePromotion({{ $customer->id }})"
                                                                        class="form-control">
                                                                        <option value="">เลือก</option>
                                                                        @foreach ($promotions->where('type_promotion', '=', 4) as $promotion)
                                                                            <option value="{{ $promotion->id }}">
                                                                                {{ $promotion->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row"
                                                                id="last_promotion_div_{{ $customer->id }}"
                                                                style="display:none;">
                                                                <div class="col-lg-12 mt-5 mb-5">
                                                                    รับโบนัสล่าสุดเมื่อ​: <span
                                                                        id="last_promotion_{{ $customer->id }}"></span>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <label for="">จำนวนโบนัสที่ได้</label>
                                                                    <input type="number" class="form-control text-right"
                                                                        name="bonus" id="bonus_{{ $customer->id }}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="pull-right">
                                                                        <button type="button"
                                                                            onclick="submitFormPromotion({{ $customer->id }})"
                                                                            class="btn btn-primary btn-spinner"
                                                                            id="btn_load_{{ $customer->id }}">
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">
                        {{ $customers->links() }}
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
        var KTBootstrapSwitch = function() {
            // Private functions
            var demos = function() {
                // minimum setup
                $('[data-switch=true]').bootstrapSwitch();

            };

            return {
                // public functions
                init: function() {
                    demos();
                },
            };
        }();

        // Class definition
        $(function() {
            KTBootstrapSwitch.init();
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

        function changePromotion(customer_id) {

            var promotion = $('#promotion_id_' + customer_id).val();

            $('#last_promotion_' + customer_id).html('');

            if (promotion == '') {
                $('#last_promotion_div_' + customer_id).hide();
            } else {
                $('#last_promotion_div_' + customer_id).show();
                $.post('{{ route('agent.customer.last-promotion') }}', {
                    customer_id: customer_id,
                    promotion_id: promotion
                }, function(r) {
                    $('#last_promotion_' + customer_id).html(r);
                });
            }

        }

        function updateCredit(customer_id) {

            $('#customerCredit_' + customer_id).hide();

            $.post('{{ route('agent.check-credit') }}', {
                customer_id: customer_id
            }, function(r) {
                $('#customerCredit_' + customer_id).html(r.data.credit);
                $('#customerCredit_' + customer_id).show();
            });

        }

        function submitFormPromotion(customer_id) {

            var form = $('#form_promotion_' + customer_id).serializeArray();

            var promotion = $('#promotion_id_' + customer_id).val();

            console.log(form);

            var bonus = $('#bonus_' + customer_id).val();

            if (promotion == '') {
                $.notify({
                    // options
                    message: 'กรุณาระบุโปรโมชั่น'
                }, {
                    // settings
                    type: 'danger',
                    animate: {
                        enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                    },
                    placement: {
                        from: "top",
                        align: "right"
                    },
                });
                return;
            }

            if (bonus <= 0) {
                $.notify({
                    // options
                    message: 'กรุณาระบจำนวนโบนัส'
                }, {
                    // settings
                    type: 'danger',
                    animate: {
                        enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                    },
                    placement: {
                        from: "top",
                        align: "right"
                    },
                });
                return;
            }

            if (confirm('ยืนยัน ?')) {

                $('#btn_load_' + customer_id).buttonLoader('start');
                $.post('{{ route('agent.customer.promotion') }}', form, function(r) {
                    if (r.status === false) {
                        $.notify({
                            // options
                            message: r.message
                        }, {
                            // settings
                            type: 'danger',
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                            placement: {
                                from: "top",
                                align: "right"
                            },
                        });
                    } else {

                        $.notify({
                            // options
                            message: 'เพิ่มโบนัสเสร็จแล้ว'
                        }, {
                            // settings
                            type: 'success',
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                            placement: {
                                from: "top",
                                align: "right"
                            },
                        });
                        $('#last_promotion_div_' + customer_id).hide();
                        $('#last_promotion_' + customer_id).html('');
                        $('#promotionCustomer_' + customer_id).modal('hide');
                    }
                    $('#btn_load_' + customer_id).buttonLoader('stop');
                });

            }

        }

        function clearPromotion(customer_id, promotion_cost_id) {

            if (confirm('ยืนยัน ?')) {

                $.post('{{ route('agent.promotion.clear') }}', {
                    customer_id: customer_id,
                    promotion_cost_id: promotion_cost_id,
                }, function(r) {
                    location.reload();
                });

            }

        }

        function resetPassword(customer_id) {

            $.post('{{ route('agent.report.customer-password') }}', {
                customer_id: customer_id
            }, function(r) {
                location.reload();
            });

        }
    </script>
@endsection
