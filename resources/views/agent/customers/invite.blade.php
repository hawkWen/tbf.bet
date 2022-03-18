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
                    {{-- <form action="{{route('agent.customer')}}">
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
                    <h3> <i class="fa fa-handshake"></i> รายชื่อเอเย่นต์</h3>
                    <hr>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td></td>
                                <td>ชื่อเอเย่นต์</td>
                                <td>ดูรายละเอียด</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agents as $agent)
                                <tr>
                                    <td align="center" width="50">
                                        @if ($agent->img_url == '')
                                            <img src="https://via.placeholder.com/50" class="img-fluid img-circle" alt="">
                                        @else
                                            <img src="{{ $agent->img_url }}" class="img-fluid img-circle" width="50"
                                                alt="">
                                        @endif
                                    </td>
                                    <td>
                                        <span class="pull-right">

                                            จำนวนเพื่อน {{ number_format($agent->invites->count()) }} คน
                                        </span>
                                        {{ $agent->name }}
                                    </td>
                                    <td width="100" align="center">
                                        <a href="{{ route('agent.invite.show', $agent->id) }}" class="btn btn-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
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

        function submitFormPromotion(customer_id) {

            var form = $('#form_promotion_' + customer_id).serializeArray();

            var promotion = $('#promotion_id_' + customer_id).val();

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
                        $('#last_promotion_div_' + customer_id).hide();
                        $('#last_promotion_' + customer_id).html('');
                        $('#promotionCustomer_' + customer_id).modal('hide');
                    }
                    $('#btn_load_' + customer_id).buttonLoader('stop');
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
