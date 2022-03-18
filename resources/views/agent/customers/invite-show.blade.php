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
                        <div class="tab">
                            <a href="{{ route('agent.customer') }}">
                                <h3 class="mb-0">จัดการลูกค้า</h3>
                            </a>
                        </div>
                        <div class="tab active">
                            <a href="{{ route('agent.invite') }}">
                                <h3 class="mb-0">ระบบแนะนำเพื่อน</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="pull-right">
                        จำนวนเพื่อนทั้งหมด {{ $agent->invites->count() }} <i class="fa fa-users pr-2 pl-2"></i>
                    </div>
                    <h3> <i class="fa fa-handshake mr-2"></i> เอเย่นต์ {{ $agent->name }} ({{ $agent->username }})</h3>
                    {{-- <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#addPromotionInviteModal">
                        <i class="fa fa-plus"></i>
                        เพิ่มโบนัสแนะนำเพื่อน
                    </button> --}}

                    <!-- Modal-->
                    {{-- <div class="modal fade" id="addPromotionInviteModal" data-backdrop="static" tabindex="-1" role="dialog"
                        aria-labelledby="staticBackdrop" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form id="form_promotion_">
                                    <input type="hidden" name="customer_id" value="{{ $agent->id }}">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มโปรโมชั่นพิเศษ</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="">เลือกโปรโมชั่นที่ให้</label>
                                                <select name="promotion_id" id="promotionId"
                                                    onchange="changePromotion({{ $agent->id }})" class="form-control">
                                                    <option value="">เลือก</option>
                                                    @foreach ($promotions->where('type_promotion', '=', 5) as $promotion)
                                                        <option value="{{ $promotion->id }}">{{ $promotion->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="last_promotion_div_" style="display:none;">
                                            <div class="col-lg-12 mt-5 mb-5">
                                                รับโบนัสล่าสุดเมื่อ​: <span id="last_promotion_"></span>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="">จำนวนโบนัสที่ได้</label>
                                                <input type="number" class="form-control text-right" name="bonus"
                                                    id="bonus_" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="pull-right">
                                                    <button type="button"
                                                        onclick="submitFormPromotion({{ $agent->id }})"
                                                        class="btn btn-primary btn-spinner" id="btn_load_">
                                                        <i class="fa fa-check"></i>
                                                        ยืนยัน
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
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
                    </div> --}}
                    <div class="clearfix"></div>
                    <hr>
                    {{-- <form action="{{route('agent.invite.show', $agent->id)}}" method="get">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="">เดือนที่เติมเงิน</label>
                            <select name="month" id="month" class="form-control">
                                <option value="01" @if ($month == '01') selected @endif>มกราคม</option>
                                <option value="02" @if ($month == '02') selected @endif>กุมภาพันธ์</option>
                                <option value="03" @if ($month == '03') selected @endif>มีนาคม</option>
                                <option value="04" @if ($month == '04') selected @endif>เมษายน</option>
                                <option value="05" @if ($month == '05') selected @endif>พฤษภาคม</option>
                                <option value="06" @if ($month == '06') selected @endif>มิถุนายน</option>
                                <option value="07" @if ($month == '07') selected @endif>กรกฎาคม</option>
                                <option value="08" @if ($month == '08') selected @endif>สิงหาคม</option>
                                <option value="09" @if ($month == '09') selected @endif>กันยายน</option>
                                <option value="10" @if ($month == '10') selected @endif>ตุลาคม</option>
                                <option value="11" @if ($month == '11') selected @endif>พฤศจิกายน</option>
                                <option value="12" @if ($month == '12') selected @endif>ธันวาคม</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="">ชื่อลูกค้า</label>
                            <input type="text" class="form-control" name="name" value="{{$name}}">
                        </div>
                        <div class="col-lg-1">
                            <button class="btn btn-primary" style="margin-top: 27px">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form> --}}
                    @if ($agent->invites)
                        {{-- <h4>รายงานลูกค้าที่เติมในเดือน {{$month}}</h4> --}}
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td width="200">ชื่อ</td>
                                    <td>Username</td>
                                    <td>ยอดฝาก</td>
                                    <td>ยอดถอน</td>
                                    <td>TurnOver</td>
                                    <td>WinLoss</td>
                                    <td>อัพเดทการเล่นล่าสุด</td>
                                    <td>ยอดฝากแรก</td>
                                    <td>สมัครเมื่อวันที่</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agent->invites as $invite)
                                    <tr>
                                        <td width="200">
                                            {{ $invite->name }}
                                        </td>
                                        <td>{{ $invite->username }}
                                        </td>

                                        <td align="center">
                                            <span class="text-success">
                                                + {{ number_format($invite->deposits->sum('amount'), 2) }}
                                            </span>
                                            @if ($invite->deposits->sum('amount') > 0)
                                                <p>
                                                    ล่าสุด
                                                    {{ $invite->deposits->sortByDesc('created_at')->first()['created_at'] }}
                                                </p>
                                            @endif
                                        </td>
                                        <td align="center">
                                            <span class="text-danger">
                                                - {{ number_format($invite->withdraws->sum('amount'), 2) }}
                                            </span>
                                            @if ($invite->withdraws->sum('amount') > 0)
                                                <p>
                                                    ล่าสุด
                                                    {{ $invite->deposits->sortByDesc('created_at')->first()['created_at'] }}
                                                </p>
                                            @endif
                                        </td>
                                        @if ($brand->game_id == 1)
                                            <td align="center">
                                                {{ number_format($invite->betDetails->sum('turn_over'), 2) }}
                                            </td>
                                            <td align="center">
                                                {{ number_format($invite->betDetails->sum('win_loss'), 2) }}
                                            </td>
                                        @else
                                            <td align="center">
                                                {{ number_format($invite->bets->sum('turn_over'), 2) }}
                                            </td>
                                            <td align="center">
                                                {{ number_format($invite->bets->sum('win_loss'), 2) }}
                                            </td>
                                        @endif
                                        <td>
                                            {{ $invite->bets->sortByDesc('created_at')->first()['created_at'] }}
                                        </td>
                                        <td align="center">
                                            @if ($invite->deposits->first()['amount'] > 0)
                                                <span class="text-success">
                                                    {{ $invite->deposits->first()['amount'] }}
                                                </span>
                                                <p>
                                                    ล่าสุด
                                                    {{ $invite->deposits->first()['created_at'] }}
                                                </p>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="mb-0 pb-0">{{ $invite->created_at }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

        function changePromotion(customer_id) {

            var promotion = $('#promotionId').val();

            $('#last_promotion_').html('');

            if (promotion == '') {
                $('#last_promotion_div_').hide();
            } else {
                $('#last_promotion_div_').show();
                $.post('{{ route('agent.customer.last-promotion') }}', {
                    customer_id: customer_id,
                    promotion_id: promotion
                }, function(r) {
                    $('#last_promotion_').html(r);
                });
            }

        }

        function submitFormPromotion(customer_id) {

            var form = $('#form_promotion_').serializeArray();

            var promotion = $('#promotionId').val();

            var bonus = $('#bonus_').val();

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

                $('#btn_load_').buttonLoader('start');
                $.post('{{ route('agent.customer.promotion') }}', form, function(r) {
                    if (r.status == false) {
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
                        $('#last_promotion_div_').hide();
                        $('#last_promotion_').html('');
                        $('#promotionCustomer_').modal('hide');
                        location.reload();
                    }
                    $('#btn_load_').buttonLoader('stop');
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
