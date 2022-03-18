@extends('layouts.super')

@section('css')

@endsection

@section('content')

    <div class="content d-flex flex-column flex-column-fluid">
        {{-- <div class="d-flex flex-column-fluid"> --}}
        <div class="container-fluid">
            <p class="text-dark ">ภาพรวมประจำวัน {{ $dates['input_start_date'] }} -
                {{ $dates['input_end_date'] }}
            </p>
            {{-- <form action="{{ route('agent') }}" method="get">
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
                </form> --}}
            <div class="row">
                <div class="col-4">
                    <div class="card card-custom card-shadowless gutter-b bg-white">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark"> <i
                                        class="fas fa-people-carry mr-2"></i>
                                    แบรนด์ที่กำลังใช้งาน</span>
                                <span class="text-muted mt-3 font-weight-bold font-size-sm">จำนวน {{ $brands->count() }}
                                    แบรนด์</span>
                            </h3>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-8">
                            <!--begin::Item-->
                            <div class="row">
                                @foreach ($brands as $brand)
                                    <div class="col-lg-6">
                                        <div class="d-flex align-items-center mb-10">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-40 symbol-light-primary mr-5">
                                                <span class="symbol-label">
                                                    <img src="https://agent.casinoauto.io/storage/{{ $brand->logo }}"
                                                        class="img-fluid img-rounded" alt="">
                                                </span>
                                            </div>
                                            <!--end::Symbol-->

                                            <!--begin::Text-->
                                            <div class="d-flex flex-column font-weight-bold">
                                                <a href="#"
                                                    class="text-dark text-hover-primary mb-1 font-size-lg">{{ $brand->name }}</a>
                                                <span class="text-muted">
                                                    @if ($brand->type_api == 2)
                                                        <span class="badge badge-danger">
                                                            แบรนด์ในเครือ
                                                        </span>
                                                    @else

                                                        <span class="badge badge-primary">
                                                            แบรนด์ลูกค้า
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                            <!--end::Text-->
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <div class="col-8">
                    <div class="card card-custom card-shadowless gutter-b bg-white">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark"> <i
                                        class="fas fa-chart-line mr-2"></i>
                                    สรุปรายได้ประจำวันของแต่ละแบรนด์</span>
                            </h3>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-8">
                            <!--begin::Item-->
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>แบรนด์</td>
                                        <td>ลูกค้าใหม่</td>
                                        <td>ยอดฝาก</td>
                                        <td>ยอดขาย</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($brands as $brand)
                                        <tr>
                                            <td widtth="400">{{ $brand->name }}</td>
                                            <td align="center">{{ $brand->customerToday }}</td>
                                            <td align="center" class="text-success">
                                                {{ number_format($brand->depositToday, 2) }}</td>
                                            <td align="center" class="text-danger">
                                                {{ number_format($brand->withdrawToday, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!--end::Item-->
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

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
