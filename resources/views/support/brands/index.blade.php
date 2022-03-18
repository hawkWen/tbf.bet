@extends('layouts.support')

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
                            <a href="{{ route('support') }}" class="text-muted">ภาพรวม</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-dark">

                                จัดการแบรนด์</a>
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
                {{-- <a href="#" class="btn btn-light-primary fon --}}
                <!--end::Actions-->
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
            <div class="row">
                <input type="hidden" id="brands" value="{{ $brands->pluck('id') }}">
                @foreach ($brands as $brand)
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <!--begin::Card-->
                        <div class="card card-custom gutter-b">
                            <div class="card-body">
                                <!--begin::Top-->
                                <div class="d-flex">
                                    <!--begin::Pic-->
                                    <div class="flex-shrink-0 mr-7">
                                        <div class="symbol symbol-50 symbol-lg-120">
                                            <img alt="Pic" src="{{ $brand->logo_url }}">
                                        </div>
                                    </div>
                                    <!--end::Pic-->

                                    <!--begin: Info-->
                                    <div class="flex-grow-1">
                                        <!--begin::Title-->
                                        <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                                            <!--begin::User-->
                                            <div class="mr-3">
                                                <!--begin::Name-->
                                                <a href="#"
                                                    class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">
                                                    {{ $brand->name }}

                                                </a>
                                                <!--end::Name-->

                                                <!--begin::Contacts-->
                                                <div class="d-flex flex-wrap my-2">
                                                    <a href="#"
                                                        class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                                        <i class="fab fa-line"></i>
                                                        <span>@</span>{{ $brand->line_id }}
                                                    </a>
                                                    <a href="#"
                                                        class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                                        <i class="fa fa-globe"></i>
                                                        {{ $brand->subdomain }}.{{ env('APP_NAME') }}.{{ env('APP_DOMAIN') }}
                                                    </a>
                                                </div>
                                                <!--end::Contacts-->
                                            </div>
                                            <!--begin::User-->

                                            <!--begin::Actions-->
                                            <div class="my-lg-0 my-1">
                                                <a href="#"
                                                    class="btn btn-sm btn-light-warning font-weight-bolder text-uppercase mr-2"
                                                    data-toggle="modal"
                                                    data-target="#editBrandModal_{{ $brand->id }}">แก้ไข</a>
                                                <a href="#" class="btn btn-sm btn-danger font-weight-bolder text-uppercase"
                                                    data-toggle="modal"
                                                    data-target="#deleteBrandModal_{{ $brand->id }}">ลบ</a>
                                            </div>
                                            <!--end::Actions-->
                                        </div>
                                        <!--end::Title-->

                                        <!--begin::Content-->
                                        <div class="d-flex align-items-center flex-wrap justify-content-between">
                                            <!--begin::Description-->
                                            <div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5">
                                                <p>
                                                    <b>เกมส์ที่ให้บริการ</b> : {{ $brand->game->name }}
                                                </p>
                                            </div>
                                            <!--end::Description-->

                                            <!--begin::Progress-->
                                            <div class="d-flex mt-4 mt-sm-0">
                                                {{-- <span class="font-weight-bold mr-4">Progress</span>
                                            <div class="progress progress-xs mt-2 mb-2 flex-shrink-0 w-150px w-xl-250px">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 63%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div> --}}
                                                {{-- <span class="font-weight-bolder text-dark ml-4">78%</span> --}}
                                            </div>
                                            <!--end::Progress-->
                                        </div>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Top-->

                                <!--begin::Separator-->
                                <div class="separator separator-solid my-7"></div>
                                <!--begin::Bottom-->
                                {{-- <div class="d-flex align-items-center flex-wrap">
                                <!--begin: Item-->
                                <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                    <span class="mr-4">
                                        <i class="flaticon-piggy-bank icon-2x text-muted font-weight-bold"></i>
                                    </span>
                                    <div class="d-flex flex-column text-dark-75">
                                        <span class="font-weight-bolder font-size-sm">ยอดเติมเงิน</span>
                                        <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">$</span>249,500</span>
                                    </div>
                                </div>
                                <!--end: Item-->
                    
                                <!--begin: Item-->
                                <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                    <span class="mr-4">
                                        <i class="flaticon-coins icon-2x text-muted font-weight-bold"></i>
                                    </span>
                                    <div class="d-flex flex-column text-dark-75">
                                        <span class="font-weight-bolder font-size-sm">ยอดถอนเงิน</span>
                                        <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">$</span>164,700</span>
                                    </div>
                                </div>
                                <!--end: Item-->
                    
                                <!--begin: Item-->
                                <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                    <span class="mr-4">
                                        <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                                    </span>
                                    <div class="d-flex flex-column flex-lg-fill">
                                        <span class="text-dark-75 font-weight-bolder font-size-sm">จำนวนใบงาน</span>
                                        <span class="font-weight-bolder font-size-h5">164,700</span>
                                    </div>
                                </div>
                                <!--end: Item-->
                    
                                <!--begin: Item-->
                                <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                    <span class="mr-4">
                                        <i class="flaticon-users icon-2x text-muted font-weight-bold"></i>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark-75 font-weight-bolder font-size-sm">จำนวนลูกค้า</span>
                                        <span class="font-weight-bolder font-size-h5">164,700</span>
                                    </div>
                                </div>
                                <!--end: Item-->
                    
                            </div> --}}
                                <!--end::Bottom-->
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!-- Modal-->
                    <div class="modal fade" id="editBrandModal_{{ $brand->id }}" data-backdrop="static"
                        tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                        <div class="modal-dialog modal-extra-lg" role="document">
                            <form action="{{ route('support.brand.update') }}" method="post" id="formUpdatebrand"
                                enctype="multipart/form-data">
                                <input type="hidden" name="brand_id" value="{{ $brand->id }}" />
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">แก้ไขแบรนด์</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"
                                            aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-3 col-2">
                                                        <label for="">โลโก้แบรนด์</label>
                                                        <br>
                                                        <div class="image-input image-input-outline"
                                                            id="kt_image_{{ $brand->id }}">
                                                            <div class="image-input-wrapper"
                                                                style="background-image: url({{ $brand->logo_url }})">
                                                            </div>

                                                            <label
                                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                                data-action="change" data-toggle="tooltip" title=""
                                                                data-original-title="Upload Logo">
                                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                                <input type="file" name="logo" accept=".png, .jpg, .jpeg" />
                                                                <input type="hidden" name="logo_remove" />
                                                            </label>

                                                            <span
                                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                                data-action="cancel" data-toggle="tooltip"
                                                                title="Cancel Logo">
                                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 col-10">
                                                        <div class="col-12">
                                                            <label for="">ชื่อแบรนด์</label>
                                                            <input type="text" class="form-control" name="name"
                                                                input-type="character" value="{{ $brand->name }}">
                                                        </div>
                                                        <div class="col-12">
                                                            <label for="">LINE_ID</label>
                                                            <input type="text" class="form-control" name="line_id"
                                                                input-type="character" value="{{ $brand->line_id }}">
                                                        </div>
                                                        <div class="col-12">
                                                            <label for="">เบอร์โทรศัพท์ติดต่อ</label>
                                                            <input type="text" class="form-control" name="telephone"
                                                                input-type="telephone" value="{{ $brand->telephone }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-lg-6">
                                                        <label for="">APP ID</label>
                                                        <input type="text" class="form-control" name="app_id"
                                                            value="{{ $brand->app_id }}">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="">Hash</label>
                                                        <input type="text" class="form-control" name="hash"
                                                            value="{{ $brand->hash }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label for="">Sub Domain</label>
                                                        <input type="text" class="form-control" name="subdomain"
                                                            input-type="character" value="{{ $brand->subdomain }}"
                                                            readonly disabled>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <label for="">&nbsp;</label>
                                                        <input type="text" value=".casinoauto.io" class="form-control"
                                                            readonly disabled>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <label for="">เกมส์ที่ให้บริการ</label>
                                                        <select name="game_id" id="game_id" class="form-control">
                                                            <option value="">เลือก</option>
                                                            @foreach ($games as $game)
                                                                <option value="{{ $game->id }}"
                                                                    @if ($brand->game_id == $game->id) selected @endif>
                                                                    {{ $game->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="">Agent Prefix</label>
                                                        <input type="text" class="form-control" name="agent_prefix"
                                                            value="{{ $brand->agent_prefix }}">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="">Agent Username</label>
                                                        <input type="text" class="form-control" name="agent_username"
                                                            value="{{ $brand->agent_username }}">
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <label for="">Agent Password</label>
                                                        <input type="text" class="form-control" name="agent_password"
                                                            value="{{ $brand->agent_password }}">
                                                    </div>
                                                </div>
                                                {{-- <div class="row">
                                                <div class="col-lg-12">
                                                    <label for="">ดักค่าสำหรับ UFAbet</label>
                                                    <textarea name="agent_member_value" id="" cols="30" rows="10" class="form-control">
                                                        {{$brand->agent_member_value}}
                                                    </textarea>
                                                </div>
                                            </div> --}}
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <label for="">ถือหุ้น (%)</label>
                                                        <input type="text" class="form-control" name="stock"
                                                            input-type="money_decimal" placeholder="0.00"
                                                            value="{{ $brand->stock }}">
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="">ค่าใช้จ่ายต่อรายเดือน</label>
                                                        <input type="text" class="form-control" name="cost_service"
                                                            input-type="money_decimal" placeholder="0.00"
                                                            value="{{ $brand->cost_service }}">
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="">ค่าใช้จ่ายต่อใบงาน</label>
                                                        <input type="text" class="form-control" name="cost_working"
                                                            input-type="money_decimal" placeholder="0.00"
                                                            value="{{ $brand->cost_working }}">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="">เติมเงินขั้นต่ำ</label>
                                                        <input type="text" class="form-control" name="deposit_min"
                                                            input-type="money_decimal" placeholder="0.00"
                                                            value="{{ $brand->deposit_min }}">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="">ถอนเงินขั้นต่ำ</label>
                                                        <input type="text" class="form-control" name="withdraw_min"
                                                            input-type="money_decimal" placeholder="0.00"
                                                            value="{{ $brand->withdraw_min }}">
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <label for="">ถอนอัตโนมัติในวงเงินไม่เกิน </label>
                                                        <input type="text" class="form-control" name="withdraw_auto_max"
                                                            input-type="money_decimal" placeholder="10,000"
                                                            value="{{ $brand->withdraw_auto_max }}">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <div class="checkbox-inline">
                                                                <label class="checkbox">
                                                                    <input type="checkbox" name="status_telephone" value="1"
                                                                        @if ($brand->status_telephone == 1) checked @endif>
                                                                    <span></span>
                                                                    เก็บข้อมูลเบอร์โทรศัพท์
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="checkbox-inline">
                                                                <label class="checkbox">
                                                                    <input type="checkbox" name="status_line_id" value="1"
                                                                        @if ($brand->status_line_id == 1) checked @endif>
                                                                    <span></span>
                                                                    เก็บไอดีไลน์
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label>ประเภทการเติมเงิน</label>
                                                            <div class="radio-list">
                                                                <label class="radio">
                                                                    <input type="radio" name="type_deposit" value="1"
                                                                        @if ($brand->type_deposit == 1) checked @endif>
                                                                    <span></span>
                                                                    บอทเติม
                                                                </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="type_deposit" value="2"
                                                                        @if ($brand->type_deposit == 2) checked @endif>
                                                                    <span></span>
                                                                    โอนสลิป
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label>แจ้งเตือนสมัครสมาชิก</label>
                                                            <div class="radio-list">
                                                                <label class="radio">
                                                                    <input type="radio" name="noty_register" value="1"
                                                                        @if ($brand->noty_register == 1) checked @endif>
                                                                    <span></span>
                                                                    เปิด
                                                                </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="noty_register" value="0"
                                                                        @if ($brand->noty_register == 2) checked @endif>
                                                                    <span></span>
                                                                    ปิด
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label>แจ้งเตือนการเติมเงิน</label>
                                                            <div class="radio-list">
                                                                <label class="radio">
                                                                    <input type="radio" name="noty_deposit" value="1"
                                                                        @if ($brand->noty_deposit == 1) checked @endif>
                                                                    <span></span>
                                                                    เปิด
                                                                </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="noty_deposit" value="0"
                                                                        @if ($brand->noty_deposit == 2) checked @endif>
                                                                    <span></span>
                                                                    ปิด
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label>แจ้งเตือนการถอนเงิน</label>
                                                            <div class="radio-list">
                                                                <label class="radio">
                                                                    <input type="radio" name="noty_withdraw" value="1"
                                                                        @if ($brand->noty_withdraw == 1) checked @endif>
                                                                    <span></span>
                                                                    เปิด
                                                                </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="noty_withdraw" value="0"
                                                                        @if ($brand->noty_withdraw == 2) checked @endif>
                                                                    <span></span>
                                                                    ปิด
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="">LINE TOKEN</label>
                                                            <input type="text" class="form-control" name="line_token"
                                                                value="{{ $brand->line_token }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="">LINE CHANNEL SECRET</label>
                                                            <input type="text" class="form-control"
                                                                name="line_channel_secret"
                                                                value="{{ $brand->line_channel_secret }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="">LINE CONNECT</label>
                                                            <input type="text" class="form-control"
                                                                name="line_liff_connect"
                                                                value="{{ $brand->line_liff_connect }}">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-warning font-weight-bold"
                                            data-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn btn-warning font-weight-bold">บันทึก</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Modal-->
                    <div class="modal fade" id="deleteBrandModal_{{ $brand->id }}" data-backdrop="static"
                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('support.brand.delete') }}" method="post">
                                <input type="hidden" name="brand_id" value="{{ $brand->id }}">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">ยืนยันการลบ ?</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-danger font-weight-bold"
                                            data-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn btn-danger font-weight-bold">ยืนยัน</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row pull-right">
                <div class="">
                    {{-- {{$brands->links()}} --}}
                </div>
            </div>
            <div class="
                    modal fade" id="createBrandMomdal" data-backdrop="static" tabindex="-1"
                role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-extra-lg" role="document">
                    <form action="{{ route('support.brand.store') }}" method="post" id="formCreateBrand"
                        enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มแบรนด์</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-3 col-2">
                                                <label for="">โลโก้แบรนด์</label>
                                                <br>
                                                <div class="image-input image-input-outline" id="kt_image">
                                                    <div class="image-input-wrapper" style=""></div>

                                                    <label
                                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                        data-action="change" data-toggle="tooltip" title=""
                                                        data-original-title="Upload Logo">
                                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                                        <input type="file" name="logo" accept=".png, .jpg, .jpeg" />
                                                        <input type="hidden" name="logo_remove" />
                                                    </label>

                                                    <span
                                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                        data-action="cancel" data-toggle="tooltip" title="Cancel Logo">
                                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-lg-9 col-10">
                                                <div class="col-12">
                                                    <label for="">ชื่อแบรนด์</label>
                                                    <input type="text" class="form-control" name="name"
                                                        input-type="character">
                                                </div>
                                                <div class="col-12">
                                                    <label for="">LINE_ID</label>
                                                    <input type="text" class="form-control" name="line_id"
                                                        input-type="character">
                                                </div>
                                                <div class="col-12">
                                                    <label for="">เบอร์โทรศัพท์ติดต่อ</label>
                                                    <input type="text" class="form-control" name="telephone"
                                                        input-type="telephone">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <label for="">Sub Domain</label>
                                                <input type="text" class="form-control" name="subdomain"
                                                    input-type="character">
                                            </div>
                                            <div class="col-lg-9">
                                                <label for="">&nbsp;</label>
                                                <input type="text" value=".casinoauto.io" class="form-control" readonly
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="">APP ID</label>
                                                <input type="text" class="form-control" name="app_id" value="">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="">Hash</label>
                                                <input type="text" class="form-control" name="hash" value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="">เกมส์ที่ให้บริการ</label>
                                                <select name="game_id" id="game_id" class="form-control">
                                                    <option value="">เลือก</option>
                                                    @foreach ($games as $game)
                                                        <option value="{{ $game->id }}">{{ $game->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="">Agent Prefix</label>
                                                <input type="text" class="form-control" name="agent_prefix">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="">Agent Username</label>
                                                <input type="text" class="form-control" name="agent_username">
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="">Agent Password</label>
                                                <input type="text" class="form-control" name="agent_password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="">ถือหุ้น (%)</label>
                                                <input type="text" class="form-control" name="stock"
                                                    input-type="money_decimal" placeholder="0.00">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="">ค่าใช้จ่ายต่อรายเดือน</label>
                                                <input type="text" class="form-control" name="cost_service"
                                                    input-type="money_decimal" placeholder="0.00">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="">ค่าใช้จ่ายต่อใบงาน</label>
                                                <input type="text" class="form-control" name="cost_working"
                                                    input-type="money_decimal" placeholder="0.00">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="">เติมเงินขั้นต่ำ</label>
                                                <input type="text" class="form-control" name="deposit_min"
                                                    input-type="money_decimal" placeholder="0.00">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="">ถอนเงินขั้นต่ำ</label>
                                                <input type="text" class="form-control" name="withdraw_min"
                                                    input-type="money_decimal" placeholder="0.00">
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="">ถอนอัตโนมัติในวงเงินไม่เกิน </label>
                                                <input type="text" class="form-control" name="withdraw_auto_max"
                                                    input-type="money_decimal" placeholder="10,000">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <label>ประเภทการเติมเงิน</label>
                                                    <div class="radio-list">
                                                        <label class="radio">
                                                            <input type="radio" name="type_deposit" value="1" checked>
                                                            <span></span>
                                                            บอทเติม
                                                        </label>
                                                        <label class="radio">
                                                            <input type="radio" name="type_deposit" value="2">
                                                            <span></span>
                                                            โอนสลิป
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary font-weight-bold">เพิ่ม</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
@endsection

@section('javascript')
    {!! JsValidator::formRequest('App\Http\Requests\BrandRequest', '#formCreateBrand') !!}

    <script>
        // Example 4
        var avatar_4 = new KTImageInput('kt_image');

        var brands = $('#brands').val().replace('[', '').replace(']', '').split(',');

        $.each(brands, function(k, v) {
            new KTImageInput('kt_image_' + v);
        });

        $(function() {

        });
    </script>
@endsection
