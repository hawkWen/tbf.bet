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

                                โปรโมชั่น</a>
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
                        data-target="#createPromotionModal">
                        <i class="fa fa-plus"></i>เพิ่มโปรโมชั่น</a>
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
                            โปรโมชั่น
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>

                            <tr>
                                <th>โปรโมชัน</th>
                                <th>ประเภท</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($promotions as $promotion)
                                <tr>
                                    <td>{{ $promotion->name }}</td>
                                    <td>
                                        @if ($promotion->type_promotion == 1)
                                            เติมเงินครั้งเดียวต่อวัน
                                        @elseif($promotion->type_promotion == 2)
                                            เติมเงินทุกครั้ง
                                        @elseif($promotion->type_promotion == 3)
                                            สมัครสมาชิก
                                        @elseif($promotion->type_promotion == 4)
                                            คืนยอดเสีย
                                        @elseif($promotion->type_promotion == 5)
                                            แนะนำเพื่อน
                                        @elseif($promotion->type_promotion == 6)
                                            เครดิตฟรี
                                        @endif
                                    </td>
                                    <td width="100">
                                        <input data-switch="true" type="checkbox"
                                            id="status_promotion_{{ $promotion->id }}"
                                            onchange="updateStatus({{ $promotion->id }})"
                                            @if ($promotion->status == 1) checked @endif />
                                    </td>
                                    <td width="200">
                                        <button class="btn btn-warning btn-sm" data-toggle="modal"
                                            data-target="#editPromotion_{{ $promotion->id }}">
                                            <i class="fa fa-edit"></i>
                                            แก้ไข
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deletePromotion_{{ $promotion->id }}">
                                            <i class="fa fa-trash"></i>
                                            ลบ
                                        </button>
                                        <!-- Modal-->
                                        <div class="modal fade" id="editPromotion_{{ $promotion->id }}"
                                            data-backdrop="static" tabindex="-1" role="dialog"
                                            aria-labelledby="staticBackdrop" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('agent.promotion.update') }}" method="post"
                                                        enctype="multipart/form-data">
                                                        <input type="hidden" name="promotion_id"
                                                            value="{{ $promotion->id }}">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                แก้ไขโปรโมชั่น</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-4">
                                                                    <label for="">รูปโปรโมชั่น</label>
                                                                    <br>
                                                                    <div class="image-input image-input-outline"
                                                                        id="kt_image">
                                                                        <div class="image-input-wrapper"
                                                                            style="background-image: url({{ $promotion->img_url }})">
                                                                        </div>
                                                                        <label
                                                                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                                            data-action="change" data-toggle="tooltip"
                                                                            title="" data-original-title="Upload Image">
                                                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                                                            <input type="file" name="img"
                                                                                accept=".png, .jpg, .jpeg" />
                                                                            <input type="hidden" name="img_remove" />
                                                                        </label>
                                                                        <span
                                                                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                                            data-action="cancel" data-toggle="tooltip"
                                                                            title="Cancel Image">
                                                                            <i
                                                                                class="ki ki-bold-close icon-xs text-muted"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-5 mt-5">
                                                                <div class="col-lg-12">
                                                                    <label for="">ประเภทการได้รับ</label>
                                                                    <div class="radio-inline">
                                                                        <label class="radio radio-success">
                                                                            <input type="radio" name="type_promotion"
                                                                                value="1"
                                                                                @if ($promotion->type_promotion == 1) checked @endif
                                                                                onchange="changeTypePromotion({{ $promotion->id }}, $(this))" />
                                                                            <span></span>
                                                                            เติมเงินครั้งเดียวต่อวัน
                                                                        </label>
                                                                        <label class="radio radio-success">
                                                                            <input type="radio" name="type_promotion"
                                                                                value="2"
                                                                                @if ($promotion->type_promotion == 2) checked @endif
                                                                                onchange="changeTypePromotion({{ $promotion->id }}, $(this))" />
                                                                            <span></span>
                                                                            เติมเงินทุกครั้ง
                                                                        </label>
                                                                        <label class="radio radio-success">
                                                                            <input type="radio" name="type_promotion"
                                                                                value="3"
                                                                                @if ($promotion->type_promotion == 3) checked @endif
                                                                                onchange="changeTypePromotion({{ $promotion->id }}, $(this))" />
                                                                            <span></span>
                                                                            สมัครสมาชิกใหม่
                                                                        </label>
                                                                        {{-- <label class="radio radio-success">
                                                                            <input type="radio" name="type_promotion"
                                                                                value="4"
                                                                                @if ($promotion->type_promotion == 4) checked @endif />
                                                                            <span></span>
                                                                            คืนยอดเสีย
                                                                        </label>
                                                                        <label class="radio radio-success">
                                                                            <input type="radio" name="type_promotion"
                                                                                value="5"
                                                                                @if ($promotion->type_promotion == 5) checked @endif />
                                                                            <span></span>
                                                                            แนะนำเพื่อน
                                                                        </label> --}}
                                                                        <label class="radio radio-success">
                                                                            <input type="radio" name="type_promotion"
                                                                                value="6"
                                                                                @if ($promotion->type_promotion == 6) checked @endif />
                                                                            <span></span>
                                                                            เครดิตฟรี
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row"
                                                                id="typePromotion_{{ $promotion->id }}_1"
                                                                @if ($promotion->type_promotion != 1) style="display: none;" @endif>
                                                                <div class="col-lg-3">
                                                                    <label for="">
                                                                        จำนวน ... ครั้ง/วัน
                                                                    </label>
                                                                    <input type="number"
                                                                        value="{{ $promotion->amount_per_day }}"
                                                                        name="amount_per_day" class="form-control">
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label for="">ชื่อโปรโมชั่น</label>
                                                                    <input type="text" class="form-control" name="name"
                                                                        placeholder="สมัครใหม่ฟรี 100% สูงสุด 300 บาท เทิร์น 5 เท่า"
                                                                        value="{{ $promotion->name }}" />
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <label for="">ขั้นต่ำ</label>
                                                                    <input type="text" class="form-control" name="min"
                                                                        placeholder="100" input-type="money_decimal"
                                                                        value="{{ $promotion->min }}" />
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <label for="">สูงสุด</label>
                                                                    <input type="text" class="form-control" name="max"
                                                                        placeholder="300" input-type="money_decimal"
                                                                        value="{{ $promotion->max }}" />
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12 mt-1">
                                                                    <label for="">ถอนสูงสุดจำนวนเงิน (บาท)</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text">
                                                                                <input type="radio" name="withdraw_max_type"
                                                                                    value="1"
                                                                                    onchange="changeWithdrawTypeMax({{ $promotion->id }},$(this))"
                                                                                    @if ($promotion->withdraw_max_type == 1) checked @endif>
                                                                            </div>
                                                                        </div>
                                                                        <input type="text" class="form-control"
                                                                            input-type="money_decimal" name="withdraw_max"
                                                                            id="withdrawMax_{{ $promotion->id }}_1"
                                                                            placeholder="ตัวอย่าง 50,000 บาท "
                                                                            @if ($promotion->withdraw_max_type == 2) disabled @else value="{{ $promotion->withdraw_max }}" @endif>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 mt-1">
                                                                    <label for="">ถอนสูงสุดกี่เท่า
                                                                        (คูณกับยอดเติมเงิน)
                                                                    </label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text">
                                                                                <input type="radio" name="withdraw_max_type"
                                                                                    value="2"
                                                                                    onchange="changeWithdrawTypeMax({{ $promotion->id }},$(this))"
                                                                                    @if ($promotion->withdraw_max_type == 2) checked @endif>
                                                                            </div>
                                                                        </div>
                                                                        <input type="text" class="form-control"
                                                                            input-type="money" name="withdraw_max"
                                                                            id="withdrawMax_{{ $promotion->id }}_2"
                                                                            placeholder="ตัวอย่าง 10 เท่า"
                                                                            @if ($promotion->withdraw_max_type == 1) disabled  @else value="{{ $promotion->withdraw_max }}" @endif>>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if ($promotion->type_promotion == 5)
                                                                <div class="row">
                                                                    <div class="col-lg-12 mt-5 mb-5"
                                                                        id="typePromotionInvite_{{ $promotion->id }}">
                                                                        <label for="">ประเภทโปรโมชั่น แนะนำเพื่อน</label>
                                                                        <div class="radio-inline">
                                                                            <label class="radio radio-success">
                                                                                <input type="radio"
                                                                                    name="type_promotion_invite" value="1"
                                                                                    @if ($promotion->type_promotion_invite == 1) checked @endif />
                                                                                <span></span>
                                                                                ยอดฝากแรก
                                                                            </label>
                                                                            <label class="radio radio-success">
                                                                                <input type="radio"
                                                                                    name="type_promotion_invite" value="2"
                                                                                    @if ($promotion->type_promotion_invite == 2) checked @endif />
                                                                                <span></span>
                                                                                เทิร์นโอเวอร์ของเพื่อน
                                                                            </label>
                                                                            @if ($brand->game_id == 1)
                                                                                <label class="radio radio-success">
                                                                                    <input type="radio"
                                                                                        name="type_promotion_invite"
                                                                                        value="3"
                                                                                        @if ($promotion->type_promotion_invite == 3) checked @endif />
                                                                                    <span></span>
                                                                                    ยอดเสียของเพื่อน
                                                                                </label>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="row mt-5 mb-5">
                                                                <div class="col-lg-3">
                                                                    <label for="">การเพิ่มขอโบนัส</label>
                                                                    <div class="radio-list">
                                                                        @if ($promotion->type_promtoin != 6)
                                                                            <label class="radio radio-success">
                                                                                <input type="radio" name="type_cost"
                                                                                    checked="checked" value="1"
                                                                                    @if ($promotion->type_cost == 1) checked @endif />
                                                                                <span></span>
                                                                                เพิ่มเป็นเปอร์เซ็นต์ %
                                                                            </label>
                                                                        @endif
                                                                        <label class="radio radio-success">
                                                                            <input type="radio" name="type_cost" value="2"
                                                                                @if ($promotion->type_cost == 2) checked @endif />
                                                                            <span></span>
                                                                            เพิ่มเป็นจำนวนเงิน +
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <label for="">จำนวนที่ได้รับ % หรือ บาท </label>
                                                                    <input type="text" class="form-control" name="cost"
                                                                        placeholder="100" input-type="money_decimal"
                                                                        value="{{ $promotion->cost }}" />
                                                                </div>
                                                            </div>
                                                            <div class="row mt-5 mb-5">
                                                                <div class="col-lg-3">
                                                                    <label for="">ประเภทเทิร์นโอเวอร์</label>
                                                                    <div class="radio-list">
                                                                        <label class="radio radio-success">
                                                                            <input type="radio" name="type_turn_over"
                                                                                checked="checked" value="1"
                                                                                @if ($promotion->type_turn_over == 1) checked @endif />
                                                                            <span></span>
                                                                            คูณตามจำนวนเงิน
                                                                        </label>
                                                                        {{-- <label class="radio radio-success">
                                                                            <input type="radio" name="type_turn_over"
                                                                                value="2" @if ($promotion->type_turn_over == 2) checked @endif />
                                                                            <span></span>
                                                                            คิดตาม win-loss
                                                                        </label> --}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <label for="">จำนวนเทิร์นโอเวอร์</label>
                                                                    <input type="text" class="form-control"
                                                                        name="turn_over" placeholder="5" input-type="money"
                                                                        value="{{ $promotion->turn_over }}" />
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <label
                                                                        for="">จำนวนเครดิตขั้นต่ำที่เทิร์นจะหลุดอัตโนมัติ</label>
                                                                    <input type="text" class="form-control"
                                                                        input-type="money_decimal"
                                                                        name="min_break_promotion"
                                                                        value="{{ $promotion->min_break_promotion }}">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-5 mb-5">
                                                                <div class="col-lg-12">
                                                                    <label for="">ประเภทโปรโมชั่น</label>
                                                                    <div class="radio-inline">
                                                                        <label class="radio radio-success">
                                                                            <input type="radio" name="type_promotion_cost"
                                                                                value="1"
                                                                                @if ($promotion->type_promotion_cost == 1) checked="checked" @endif />
                                                                            <span></span>
                                                                            ชิบเป็น
                                                                        </label>
                                                                        <label class="radio radio-success">
                                                                            <input type="radio" name="type_promotion_cost"
                                                                                value="2"
                                                                                @if ($promotion->type_promotion_cost == 2) checked="checked" @endif />
                                                                            <span></span>
                                                                            ชิบตาย (ดึงโบนัสคืน)
                                                                        </label>
                                                                        <label class="radio radio-success">
                                                                            <input type="radio" name="type_promotion_cost"
                                                                                value="3"
                                                                                @if ($promotion->type_promotion_cost == 3) checked="checked" @endif />
                                                                            <span></span>
                                                                            ได้รับโบนัสหลังจากทำเทิร์นครับ
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if ($brand->game_id == 12)
                                                                <div class="row mt-5 mb-5">
                                                                    <div class="col-lg-12 mt-5 mb-5">
                                                                        <label for="">เกมส์ที่เข้าร่วม</label>
                                                                        <div class="radio-inline">
                                                                            <label class="radio radio-success">
                                                                                <input type="radio" name="type_game"
                                                                                    value="0"
                                                                                    @if ($promotion->type_game == 0) checked @endif />
                                                                                <span></span>
                                                                                รวมทุกเกมส์
                                                                            </label>
                                                                            <label class="radio radio-success">
                                                                                <input type="radio" name="type_game"
                                                                                    value="1"
                                                                                    @if ($promotion->type_game == 1) checked @endif />
                                                                                <span></span>
                                                                                สล็อต
                                                                            </label>
                                                                            <label class="radio radio-success">
                                                                                <input type="radio" name="type_game"
                                                                                    value="2"
                                                                                    @if ($promotion->type_game == 2) checked @endif />
                                                                                <span></span>
                                                                                คาสิโน
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                class="btn btn-light-primary font-weight-bold"
                                                                data-dismiss="modal">ยกเลิก</button>
                                                            <button type="submit"
                                                                class="btn btn-primary font-weight-bold">บันทึก</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal-->
                                        <div class="modal fade" id="deletePromotion_{{ $promotion->id }}"
                                            data-backdrop="static" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('agent.promotion.delete') }}" method="post">
                                                    <input type="hidden" name="promotion_id"
                                                        value="{{ $promotion->id }}">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger">
                                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                ยืนยันการลบ ?</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                class="btn btn-light-danger font-weight-bold"
                                                                data-dismiss="modal">ยกเลิก</button>
                                                            <button type="submit"
                                                                class="btn btn-danger font-weight-bold">ยืนยัน</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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

            <!-- Modal-->
            <div class="modal fade" id="createPromotionModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('agent.promotion.store') }}" id="formCreatePromotion" method="post"
                            enctype="multipart/form-data">

                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มโปรโมชั่น</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="">รูปโปรโมชั่น</label>
                                        <br>
                                        <div class="image-input image-input-outline" id="kt_image">
                                            <div class="image-input-wrapper" style="background-image: ''"></div>
                                            <label
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="change" data-toggle="tooltip" title=""
                                                data-original-title="Upload Image">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="img" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="img_remove" />
                                            </label>
                                            <span
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="cancel" data-toggle="tooltip" title="Cancel Image">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row mt-2">
                                    <div class="col-lg-12 mt-5 mb-5">
                                        <label for="">ประเภทการได้รับ</label>
                                        <div class="radio-inline">
                                            <label class="radio radio-success">
                                                <input type="radio" name="type_promotion" value="1" checked="checked"
                                                    onchange="changeTypePromotion(0,$(this))" />
                                                <span></span>
                                                เติมเงิน
                                            </label>
                                            <label class="radio radio-success">
                                                <input type="radio" name="type_promotion" value="2"
                                                    onchange="changeTypePromotion(0, $(this))" />
                                                <span></span>
                                                เติมเงินทุกครั้ง
                                            </label>
                                            <label class="radio radio-success">
                                                <input type="radio" name="type_promotion" value="3"
                                                    onchange="changeTypePromotion(0, $(this))" />
                                                <span></span>
                                                สมัครสมาชิก
                                            </label>
                                            {{-- <label class="radio radio-success">
                                                <input type="radio" name="type_promotion" value="4"
                                                    onchange="changeTypePromotion(0, $(this))" />
                                                <span></span>
                                                คืนยอดเสีย
                                            </label> --}}
                                            {{-- <label class="radio radio-success">
                                                <input type="radio" name="type_promotion" value="5"
                                                    onchange="changeTypePromotion(0, $(this))"
                                                    @if ($brand->promotions->where('type_promotion', '=', 5)->count() > 0) disabled @endif />
                                                <span></span>
                                                แนะนำเพื่อน
                                            </label> --}}
                                            <label class="radio radio-success">
                                                <input type="radio" name="type_promotion" value="6"
                                                    onchange="changeTypePromotion(0, $(this))" />
                                                {{-- @if ($brand->promotions->where('type_promotion', '=', 6)->count() > 0) disabled @endif --}}
                                                <span></span>
                                                เครดิตฟรี
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="typePromotion_0_1">
                                    <div class="col-lg-3">
                                        <label for="">
                                            จำนวน ... ครั้ง/วัน
                                        </label>
                                        <input type="number" value="1" name="amount_per_day" class="form-control">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">ชื่อโปรโมชั่น</label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="สมัครใหม่ฟรี 100% สูงสุด 300 บาท เทิร์น 5 เท่า" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">ขั้นต่ำ</label>
                                        <input type="text" class="form-control" name="min" placeholder="100"
                                            input-type="money_decimal" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">โบนัสสูงสุด</label>
                                        <input type="text" class="form-control" name="max" placeholder="300"
                                            input-type="money_decimal" />
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-12 mt-1">

                                        <label for="">ถอนสูงสุดจำนวนเงิน (บาท)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <input type="radio" name="withdraw_max_type" value="1"
                                                        onchange="changeWithdrawTypeMax(0,$(this))" checked>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" input-type="money_decimal"
                                                name="withdraw_max" id="withdrawMax_0_1" placeholder="ตัวอย่าง 50,000 บาท ">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-1">

                                        <label for="">ถอนสูงสุดกี่เท่า (คูณกับยอดเติมเงิน)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <input type="radio" name="withdraw_max_type" value="2"
                                                        onchange="changeWithdrawTypeMax(0,$(this))">
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" input-type="money"
                                                name="withdraw_max" id="withdrawMax_0_2" placeholder="ตัวอย่าง 10 เท่า"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 mt-5 mb-5">
                                        <label for="">การเพิ่มของโบนัส</label>
                                        <div class="radio-list">
                                            <label class="radio radio-success" id="typeCost_0_1">
                                                <input type="radio" name="type_cost" checked="checked" value="1" />
                                                <span></span>
                                                เพิ่มเป็นเปอร์เซ็นต์ %
                                            </label>
                                            <label class="radio radio-success" id="typeCost_0_2">
                                                <input type="radio" name="type_cost" value="2" />
                                                <span></span>
                                                เพิ่มเป็นจำนวนเงิน +
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 mt-5 mb-5">
                                        <label for="">จำนวนที่ได้รับ % หรือ บาท </label>
                                        <input type="text" class="form-control" name="cost" placeholder="100"
                                            input-type="money_decimal" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">ประเภทเทิร์นโอเวอร์</label>
                                        <div class="radio-list">
                                            <label class="radio radio-success">
                                                <input type="radio" name="type_turn_over" checked="checked" value="1" />
                                                <span></span>
                                                คูณตามจำนวนเงิน
                                            </label>
                                            {{-- <label class="radio radio-success">
                                                <input type="radio" name="type_turn_over" value="2" />
                                                <span></span>
                                                คิดตาม win-loss
                                            </label> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <label for="">จำนวนเทิร์นโอเวอร์</label>
                                        <input type="text" class="form-control" name="turn_over" placeholder="5"
                                            input-type="money" />
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="">จำนวนเครดิตขั้นต่ำที่เทิร์นจะหลุดอัตโนมัติ</label>
                                        <input type="text" class="form-control" input-type="money_decimal"
                                            name="min_break_promotion" value="20">
                                    </div>
                                    <div class="col-lg-12 mt-5 mb-5">
                                        <label for="">ประเภทโปรโมชั่น</label>
                                        <div class="radio-inline">
                                            <label class="radio radio-success">
                                                <input type="radio" name="type_promotion_cost" value="1"
                                                    checked="checked" />
                                                <span></span>
                                                ชิบเป็น
                                            </label>
                                            <label class="radio radio-success">
                                                <input type="radio" name="type_promotion_cost" value="2" />
                                                <span></span>
                                                ชิบตาย (ดึงโบนัสคืน)
                                            </label>
                                            <label class="radio radio-success">
                                                <input type="radio" name="type_promotion_cost" value="3" />
                                                <span></span>
                                                ได้รับโบนัสหลังจากทำเทิร์นครับ
                                            </label>
                                        </div>
                                    </div>
                                    @if ($brand->game_id == 12)
                                        <div class="col-lg-12 mt-5 mb-5">
                                            <label for="">เกมส์ที่เข้าร่วม</label>
                                            <div class="radio-inline">
                                                <label class="radio radio-success">
                                                    <input type="radio" name="type_game" value="0" checked />
                                                    <span></span>
                                                    รวมทุกเกมส์
                                                </label>
                                                <label class="radio radio-success">
                                                    <input type="radio" name="type_game" value="1" />
                                                    <span></span>
                                                    สล็อต
                                                </label>
                                                <label class="radio radio-success">
                                                    <input type="radio" name="type_game" value="2" />
                                                    <span></span>
                                                    คาสิโน
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary font-weight-bold">เพิ่ม</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
@endsection

@section('javascript')
    {!! JsValidator::formRequest('App\Http\Requests\PromotionRequest', '#formCreatePromotion') !!}

    <script>
        function updateStatus(promotion_id) {

            var status = ($('#status_promotion_' + promotion_id).is(':checked')) ? 1 : 0;

            $.post('{{ route('agent.promotion.update-status') }}', {
                promotion_id: promotion_id,
                status: status
            }, function() {

            });

        }

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

        jQuery(document).ready(function() {
            KTBootstrapSwitch.init();
        });

        function changeWithdrawTypeMax(id, ele) {

            if (ele.val() == 1) {

                $('#withdrawMax_' + id + '_1').attr('disabled', false);

                $('#withdrawMax_' + id + '_2').attr('disabled', true);

            } else if (ele.val() == 2) {

                $('#withdrawMax_' + id + '_1').attr('disabled', true);

                $('#withdrawMax_' + id + '_2').attr('disabled', false);

            }

        }

        function changeTypePromotion(id, ele) {

            if (ele.val() == 1) {

                $('#typeCost_' + id + '_1').show();
                $('#typeCost_' + id + '_2').show();

                $('#typePromotion_' + id + '_1').show();

            } else if (ele.val() == 2) {

                $('#typeCost_' + id + '_1').show();
                $('#typeCost_' + id + '_2').show();

                $('#typePromotion_' + id + '_1').hide();

            } else if (ele.val() == 3) {

                $('#typeCost_' + id + '_1').show();
                $('#typeCost_' + id + '_2').show();

                $('#typePromotion_' + id + '_1').hide();

            } else if (ele.val() == 4) {

                $('#typeCost_' + id + '_1').show();
                $('#typeCost_' + id + '_2').show();

                $('#typePromotion_' + id + '_1').hide();

            } else if (ele.val() == 5) {

                $('#typePromotionInvite_' + id).fadeIn();

                $('#typeCost_0_1').show();
                $('#typeCost_0_2').hide();

                $('#typePromotion_' + id + '_1').hide();

            } else if (ele.val() == 6) {

                $('#typeCost_' + id + '_1').hide();
                $('#typeCost_' + id + '_2').show();

                $('#typePromotion_' + id + '_1').hide();

                $('#typePromotionInvite_' + id).fadeOut('fast');

            } else {

                $('#typeCost_' + id + '_1').hide();
                $('#typeCost_' + id + '_2').show();

                $('#typePromotion_' + id + '_1').hide();

                $('#typePromotionInvite_' + id).fadeOut('fast');

            }
        }
    </script>
@endsection
