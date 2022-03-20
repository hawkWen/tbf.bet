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
                            <a href="" class="text-white">

                                ประวัติการปรับเครดิต</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
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
                            ข้อมูลลูกค้า {{ $customer->name }} ({{ $customer->username }})
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true"> <i class="fas fa-credit-card mr-2"></i>
                                ประวัติการเติม/ถอน</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="promotion-tab" data-toggle="tab" href="#promotion" role="tab"
                                aria-controls="promotion" aria-selected="true"> <i class="fas fa-gifts mr-2"></i>
                                ประวัติการรับโปรโมชั่น</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>วันที่และเวลา</th>
                                        <th>ไอดีลูกค้า</th>
                                        <th>เครดิตก่อนหน้า</th>
                                        <th>เครดิตที่ปรับ</th>
                                        <th>เครดิตหลังปรับ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer->creditHistories->sortByDesc('created_at')->take(50) as $credit_history)
                                        <tr>
                                            <td align="center">
                                                {{ $credit_history->created_at->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td>
                                                {{ $credit_history->customer->username }}
                                            </td>
                                            <td>
                                                {{ number_format($credit_history->amount_before) }}
                                            </td>
                                            <td>
                                                @if ($credit_history->type == 1)
                                                    <span class="text-success">
                                                        + {{ number_format($credit_history->amount, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-danger">
                                                        - {{ number_format($credit_history->amount, 2) }}
                                                    </span>
                                                @endif
                                                @if ($credit_history->promotion_id != 0)
                                                    <span class="badge badge-warning">รับโบนัส</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ number_format($credit_history->amount_after) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade show" id="promotion" role="tabpanel" aria-labelledby="promotion-tab">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>วันที่และเวลา</th>
                                        <th>โปรโมชั่นที่รับ</th>
                                        <th>โบนัสที่ได้</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer->promotionCosts->sortByDesc('created_at')->take(50) as $promotion)
                                        <tr>
                                            <td width="200">
                                                {{ $promotion->created_at->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td>
                                                @if ($promotion->promotion_id != 0)
                                                    {{ $promotion->promotion->name }}
                                                @else
                                                    โบนัสวงล้อ
                                                @endif
                                            </td>
                                            <td width="300">
                                                {{ number_format($promotion->bonus, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
            <!-- Button trigger modal-->

            <!-- Modal-->
            <div class="modal fade" id="createPromotionModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold"
                                data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary font-weight-bold">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
@endsection

@section('javascript')
    {!! JsValidator::formRequest('App\Http\Requests\PromotionRequest', '#formCreateDeposit') !!}
    <script>
        // Example 4
        var avatar_4 = new KTImageInput('kt_image');

        var promotions = $('#promotions').val().replace('[', '').replace(']', '').split(',');

        $.each(promotions, function(k, v) {
            new KTImageInput('kt_image_' + v);
        });
    </script>
@endsection
