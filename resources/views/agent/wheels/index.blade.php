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
                            <a href="#" class="text-muted">

                                ตั้งค่าวงล้อสปิน</a>
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
                    @if (!$wheel_config)
                        <a href="#" class="btn btn-primary font-weight-bolder btn-sm" data-toggle="modal"
                            data-target="#createPromotionModal">
                            <i class="fa fa-plus"></i>เพิ่มวงล้อ</a>
                        <!--end::Actions-->
                    @else
                        <div class="pull-right">

                            <div class="switch switch-outline switch-icon switch-success p-5 pull-right">
                                <label>
                                    <input type="checkbox" id="status_wheel" value="1"
                                        onchange="updateStatusWheel({{ $wheel_config->id }})"
                                        @if ($wheel_config->status == 1) checked @endif />
                                    <span>
                                    </span>เปิด/ปิด
                                </label>
                            </div>
                        </div>
                    @endif
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
            @if ($wheel_config)
                <form action="{{ route('agent.wheel.update') }}" method="post" id="formWheelUpdate">
                    <input type="hidden" name="wheel_config_id" value="{{ $wheel_config->id }}">
                    <!--begin::Card-->
                    <div class="card card-custom card-shadowless">
                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="pull-right">
                                <div class="clearfix">
                                    <button type="button" onClick="submitWheelUpdate()" class="btn btn-primary"
                                        id="btnSubmitWheelUpdate">
                                        บันทึกการตั้งค่า
                                    </button>
                                    <div id="loadingBtnSubmitWheelUpdate">
                                        <div class="spinner-border float-right" id="loadingBtnSubmitWheelUpdate">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-title pull-left mb-0">
                                <h1 class="card-label">
                                    <i class="fa fa-dharmachakra mr-1"></i>
                                    ตั้งค่าวงล้อสปิน
                                </h1>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="clearfix"></div>
                        <div class="card-body">
                            @if ($wheel_config)
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h5> <i class="fa fa-cog"></i> กำหนดค่าพื้นฐาน</h5>
                                        <input type="hidden" name="wheel_config_id" id="wheelConfigId"
                                            value="{{ $wheel_config->id }}" />
                                        <div class="clearfix"></div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label><b> จำนวนวงล้อ</b></label>
                                                <select name="slot_amount" id="slotAmount" class="form-control"
                                                    onchange="updateSlotAmount($(this).val())">
                                                    <option value="8" @if ($wheel_config->slot_amount == 8) selected @endif>8
                                                    </option>
                                                    {{-- <option value="10" @if ($wheel_config->slot_amount == 10) selected @endif>10</option> --}}
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label><b> รอบในการสปิน (ชั่วโมง)</b></label>
                                                <select name="time_hour" id="timeHour" class="form-control">
                                                    @for ($round = 1; $round <= 24; $round++)
                                                        <option value="{{ $round }}"
                                                            @if ($wheel_config->time_hour == $round) selected @endif>
                                                            {{ $round }}
                                                            ชั่วโมง
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-12 mb-4">
                                                <p for=""><b> เงื่อนไขการได้สปิน
                                                        <span id="passwordHelpInline"
                                                            class="badge badge-warning pull-right">
                                                            หมายเหตุ :
                                                            หลังจากสปินเสร็จแล้วยอดเติมสะสมจะกลับมาเป็น 0
                                                        </span></b></p>
                                                <div class="form-inline">
                                                    <div class="form-group">
                                                        <label for="inputPassword6">ยอดเติมสะสมถึง</label>
                                                        <input type="text" input-type="money_decimal"
                                                            name="amount_condition" id="amountCondition"
                                                            class="form-control mx-sm-3"
                                                            aria-describedby="passwordHelpInline"
                                                            value="{{ $wheel_config->amount_condition }}">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <p for=""><b> เงื่อนไขก่อนทำการสปิน</b></p>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="typeCondition1"
                                                        name="type_condition" value="0"
                                                        @if ($wheel_config->type_condition == 0) checked @endif>
                                                    <label class="form-check-label"
                                                        for="typeCondition1">ไม่มีเงื่อนไข</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="type_condition"
                                                        id="typeCondition2" value="1"
                                                        @if ($wheel_config->type_condition == 1) checked @endif>
                                                    <label class="form-check-label" for="typeCondition2">ดูคลิป Youtube
                                                        ก่อนสปิน</label>
                                                </div>
                                            </div>
                                            <div id="divCodeYoutube"
                                                @if ($wheel_config->type_condition != 1) style="display:none;" @endif>
                                                <div class="col-lg-12 mt-3">
                                                    <label for="" class="pb-2"> <b> โค้ด Youtube
                                                            เอาเฉพาะโค้ด</b></label>
                                                    <img src="{{ asset('images/ex_youtube.png') }}"
                                                        class="img-fluid mt-2 pb-2" width="200" alt=""
                                                        style="border-radius: 8px;">
                                                    <input type="text" class="form-control" name="code_youtube"
                                                        value="{{ $wheel_config->code_youtube }}"
                                                        placeholder="61H3hpsi8_Q">
                                                </div>
                                                <div class="col-lg-12 mt-2">
                                                    <label for=""><b>ระยะเวลาในการดู/กดข้าม</b></label>
                                                    <select type="text" class="form-control" name="time_youtube">
                                                        @for ($i = 15; $i <= 60; $i++)
                                                            <option value="{{ $i }}"
                                                                @if ($wheel_config->time_youtube == $i) selected @endif>
                                                                {{ $i }} วินาที</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="pull-right">
                                            <label for="" class="mr-2 pb-2">เลือกรูปแบบ</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="inlineRadio1" value="8"
                                                    name="slot_amount" @if ($wheel_config->slot_amount == 8) checked @endif
                                                    onchange="updateSlotAmount(8)">
                                                <label class="form-check-label" for="inlineRadio1">8 ช่อง</label>
                                            </div>
                                            {{-- <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="inlineRadio2" value="10"
                                                name="slot_amount" @if ($wheel_config->slot_amount == 10) checked @endif onchange="updateSlotAmount(10)">
                                            <label class="form-check-label" for="inlineRadio2">10 ช่อง</label>
                                        </div> --}}
                                        </div>
                                        <h5> <i class="fas fa-th"></i> กำหนดค่าสล็อตแต่ละช่อง
                                        </h5>
                                        <hr>
                                        <span class="badge badge-warning pull-right mb-2">หมายเหตุ : การปรับ % ต้อง รวม %
                                            ของ
                                            item
                                            ทั้งหมด ให้ได้
                                            100% ถ้า
                                            เกินหรือขาด อาจจะเกิดข้อผิดพลาดได้ </span>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ช่อง</th>
                                                    <th>ประเภทรางวัล</th>
                                                    {{-- <th>เครดิต</th> --}}
                                                    <th>เครดิตฟรีติดเงื่อนไข</th>
                                                    <th>รางวัลอื่นๆ</th>
                                                    <th>โอกาส (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($wheel_config->slot_amount == 8)
                                                    @php
                                                        $total_chance = 0;
                                                    @endphp
                                                    @foreach ($wheel_config->wheelSlotEights as $key_eight => $wheel_slot)
                                                        <tr>
                                                            <td>{{ $key_eight + 1 }}</td>
                                                            <td>
                                                                <input type="hidden"
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][id]"
                                                                    value="{{ $wheel_slot->id }}">
                                                                <select
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][type]"
                                                                    class="form-control"
                                                                    id="selectTypeRow_{{ $wheel_slot->id }}"
                                                                    onchange="selectTypeRow({{ $wheel_slot->id }}, $(this).val())">
                                                                    <option value="0"
                                                                        @if ($wheel_slot->type == 0) selected @endif>
                                                                        โปรโมชั่นเครดิตฟรี</option>
                                                                    {{-- <option value="1" @if ($wheel_slot->type == 1) selected @endif>
                                                                        โบนัสเครดิต
                                                                    </option> --}}
                                                                    <option value="2"
                                                                        @if ($wheel_slot->type == 2) selected @endif>
                                                                        ของรางวัลอื่น ๆ
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            {{-- <td width="150">
                                                                <input type="text"
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][credit]"
                                                                    class="form-control"
                                                                    id="amountRow_{{ $wheel_slot->id }}"
                                                                    @if ($wheel_slot->type == 1) value="{{ $wheel_slot->credit }}" @else value="0" @endif placeholder="เครดิต" @if ($wheel_slot->type != 1) disabled @endif input-type="money_decimal">
                                                            </td> --}}
                                                            <td>
                                                                <select
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][promotion]"
                                                                    class="form-control"
                                                                    id="promotionRow_{{ $wheel_slot->id }}"
                                                                    @if ($wheel_slot->type != 0)  @endif>
                                                                    <option value="">เลือกเครดิตฟรี</option>
                                                                    @foreach ($promotions as $promotion)
                                                                        <option value="{{ $promotion->id }}"
                                                                            @if ($wheel_slot->type == 0) @if ($wheel_slot->promotion_id == $promotion->id) selected @endif
                                                                            @endif>
                                                                            {{ $promotion->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    id="promotionOtherRow_{{ $wheel_slot->id }}"
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][promotion_other]"
                                                                    placeholder="รางวัลอื่น ๆ" class="form-control"
                                                                    @if ($wheel_slot->type == 2) value="{{ $wheel_slot->promotion_other }}" @endif
                                                                    @if ($wheel_slot->type != 2) disabled @endif />
                                                            </td>
                                                            <td width="100">
                                                                <select
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][chance]"
                                                                    id="changeRow_{{ $i }}"
                                                                    class="form-control"
                                                                    onChange="selectChanceRow({{ $i }})" chance>
                                                                    @for ($i = 0; $i <= 100; $i++)
                                                                        <option value="{{ $i }}"
                                                                            @if ($wheel_slot->chance == $i) selected @endif>
                                                                            {{ $i }} %
                                                                        </option>
                                                                    @endfor
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $total_chance += $wheel_slot->chance;
                                                        @endphp
                                                    @endforeach
                                                    <input type="hidden" name="total_chance" id="totalChance"
                                                        value="{{ $total_chance }}" />
                                                @elseif($wheel_config->slot_amount == 10)
                                                    @php
                                                        $total_chance = 0;
                                                    @endphp
                                                    @foreach ($wheel_config->wheelSlotTens as $key_ten => $wheel_slot)
                                                        <tr>
                                                            <td>{{ $key_ten + 1 }}</td>
                                                            <td>
                                                                <input type="hidden"
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][id]"
                                                                    value="{{ $wheel_slot->id }}">
                                                                <select
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][type]"
                                                                    class="form-control"
                                                                    id="selectTypeRow_{{ $wheel_slot->id }}"
                                                                    onchange="selectTypeRow({{ $wheel_slot->id }}, $(this).val())">
                                                                    <option value="0"
                                                                        @if ($wheel_slot->type == 0) selected @endif>
                                                                        โปรโมชั่นเครดิตฟรี
                                                                    </option>
                                                                    {{-- <option value="1" @if ($wheel_slot->type == 1) selected @endif>
                                                                        โบนัสเครดิต
                                                                    </option> --}}
                                                                    <option value="2"
                                                                        @if ($wheel_slot->type == 2) selected @endif>
                                                                        ของรางวัลอื่น ๆ
                                                                    </option>
                                                                </select>
                                                            </td>
                                                            {{-- <td width="150">
                                                                <input type="text"
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][credit]"
                                                                    class="form-control"
                                                                    id="amountRow_{{ $wheel_slot->id }}" placeholer="0"
                                                                    @if ($wheel_slot->type == 1) value="{{ $wheel_slot->credit }}" @endif @if ($wheel_slot->type != 1) disabled @endif input-type="money_decimal">
                                                            </td> --}}
                                                            <td>
                                                                <select
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][promotion]"
                                                                    class="form-control"
                                                                    id="promotionRow_{{ $wheel_slot->id }}"
                                                                    @if ($wheel_slot->type != 0)  @endif>
                                                                    <option value="">เลือกเครดิตฟรี</option>
                                                                    @foreach ($promotions as $promotion)
                                                                        <option value="{{ $promotion->id }}"
                                                                            @if ($wheel_slot->type == 0) @if ($wheel_slot->promotion_id == $promotion->id) selected @endif
                                                                            @endif>
                                                                            {{ $promotion->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    id="promotionOtherRow_{{ $wheel_slot->id }}"
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][promotion_other]"
                                                                    placeholder="รางวัลอื่น ๆ" class="form-control"
                                                                    @if ($wheel_slot->type == 2) value="{{ $wheel_slot->promotion_other }}" @endif
                                                                    @if ($wheel_slot->type != 2) disabled @endif />
                                                            </td>
                                                            <td width="100">
                                                                <select
                                                                    name="wheel_slot_configs[{{ $wheel_slot->id }}][chance]"
                                                                    id="changeRow_{{ $i }}"
                                                                    class="form-control"
                                                                    onChange="selectChanceRow({{ $i }})" chance>
                                                                    @for ($i = 0; $i <= 100; $i++)
                                                                        <option value="{{ $i }}"
                                                                            @if ($wheel_slot->chance == $i) selected @endif>
                                                                            {{ $i }} %
                                                                        </option>
                                                                    @endfor
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $total_chance += $wheel_slot->chance;
                                                        @endphp
                                                    @endforeach
                                                    <input type="hidden" name="total_chance" id="totalChance"
                                                        value="{{ $total_chance }}" />
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Card-->
                    <!-- Button trigger modal-->
                </form>
            @endif
            <!-- Modal-->
            <div class="modal fade" id="createPromotionModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('agent.wheel.store') }}" method="post">
                        <input type="hidden" name="brand_id" value="{{ $brand->id }}" />
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มวงล้อ</h5>
                                <button
                                    type="butto
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                n"
                                    class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    {{-- <div class="col-lg-12">
                                        <label for="">จำนวนวงล้อ</label>
                                        <select name="slot_amount" class="form-control" id="">
                                            <option value="8">8</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div> --}}
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">รอบเวลาในการสปิน</label>
                                        <select name="time_hour" class="form-control" id="">
                                            @for ($i = 1; $i <= 24; $i++)
                                                <option value="{{ $i }}">{{ $i }} ชั่วโมง
                                                </option>
                                            @endfor
                                        </select><small id="passwordHelpBlock" class="form-text text-muted">
                                            หมายเหตุ: รอบเวลาในการสปิน หมายถึง สปินแล้ว ต้องรออีกกี่ชั่วโมง
                                            ถึงจะสปินได้อีกรอบ
                                        </small>
                                    </div>
                                    <div class="col-lg-12 mt-4">
                                        ผู้สร้าง {{ Auth::user()->name }} ({{ Auth::user()->username }})
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-warning font-weight-bold"
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
    {!! JsValidator::formRequest('App\Http\Requests\PromotionRequest', '#formCreateDeposit') !!}

    {{-- {!! JsValidator::formRequest('App\Http\Requests\WheelUpdateRequest', '#formWheelUpdate') !!} --}}

    <script>
        $(function() {
            $('#loadingBtnSubmitWheelUpdate').hide();
            $('input[type=radio][name=type_condition]').change(function() {
                if (this.value == '1') {
                    $('#divCodeYoutube').show();
                } else {
                    $('#divCodeYoutube').hide();
                }
            });
        });

        function submitWheelUpdate() {

            $('#loadingBtnSubmitWheelUpdate').show();

            $('#btnSubmitWheelUpdate').hide();

            var formData = $('#formWheelUpdate').serializeArray();

            var total_chance = $('#totalChance').val();

            if (total_chance != 100) {

                $('#btnSubmitWheelUpdate').show();

                $('#loadingBtnSubmitWheelUpdate').hide();
                alert('กรุณาตรวจสอบ โอกาสการสปิน อีกคร้ังค่ะ');
                return;
            }

            $.post('{{ route('agent.wheel.update') }}', formData, function(r) {

                $('#btnSubmitWheelUpdate').show();

                $('#loadingBtnSubmitWheelUpdate').hide();
                alert('บันทึกการตั้งค่าเรียบร้อย')
            })

        }

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

        function updateStatusWheel(wheel_config_id, status) {

            var status = ($('#status_wheel').is(':checked')) ? 1 : 0;

            $.post('{{ route('agent.wheel.update-status') }}', {
                wheel_config_id: wheel_config_id,
                status: status
            }, function(r) {
                location.reload();
            });

        }

        function updateSlotAmount(amount) {

            var wheel_config_id = $('#wheelConfigId').val();

            $.post('{{ route('agent.wheel.update-slot') }}', {
                wheel_config_id: wheel_config_id,
                amount: amount
            }, function(r) {
                location.reload();
            });

        }

        function selectTypeRow(row, type) {

            if (type == 0) {

                $('#amountRow_' + row).attr('disabled', true);
                $('#promotionRow_' + row).attr('disabled', false);
                $('#promotionOtherRow_' + row).attr('disabled', false);

            } else if (type == 1) {

                $('#amountRow_' + row).attr('disabled', false);
                $('#promotionRow_' + row).attr('disabled', true);
                $('#promotionOtherRow_' + row).attr('disabled', true);

            } else if (type == 2) {

                $('#amountRow_' + row).attr('disabled', true);
                $('#promotionRow_' + row).attr('disabled', true);
                $('#promotionOtherRow_' + row).attr('disabled', false);

            }

        }

        function selectChanceRow(row) {

            var total_chance = 0;

            $('select[chance]').each(function(e) {
                var chance = $(this).val();
                total_chance += parseInt(chance);
            });

            $('#totalChance').val(total_chance);

        }
    </script>
@endsection
