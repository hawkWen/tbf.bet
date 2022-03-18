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
                            <a href="{{ route('agent.marketing.top') }}" class="text-dark">

                                การตลาด</a>
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

            @include('agent.marketings.marketing-nav')

            <div class="card mt-2">
                <div class="card-body">
                    <form action="{{ route('agent.marketing.customer') }}" method="get">
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
                    <label class="text-dark mt-1"> <i class="fa fa-info-circle"></i>
                        ช่วงเวลาการวิเคาะห์ข้อมูลอ้างอิงจากเวลา
                        00.00 ถึง 23.59
                        ของแต่ละวัน</label>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h3>ลูกค้ารู้จักจากช่องทาง</h3>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <canvas id="myChartDonut"></canvas>
                                        </div>
                                        <div class="col-lg-6">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td>ช่องทาง</td>
                                                        <td>จำนวน</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($customer_from_types as $customer_from_type)
                                                        <tr>
                                                            <td>
                                                                @if ($customer_from_type->from_type == '')
                                                                    ไม่ได้ระบุ
                                                                @else
                                                                    {{ $customer_from_type->from_type }}
                                                                @endif
                                                            </td>
                                                            <td align="right">{{ $customer_from_type->customers }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h3>พฤติกรรมการโอนเงินของลูกค้า</h3>
                                    <hr>
                                    <canvas id="myChartDonut2"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!--end::Entry-->
@endsection

@section('javascript')
    <script src="{{ asset('libs/chart.js') }}"></script>
    <script>
        function getRandomRgb() {
            var num = Math.round(0xffffff * Math.random());
            var r = num >> 16;
            var g = num >> 8 & 255;
            var b = num & 255;
            return 'rgb(' + r + ', ' + g + ', ' + b + ')';
        }
        const labelDonut = @php echo $collect_from_types; @endphp;
        const dataDonut = {
            labels: labelDonut,
            datasets: [{
                label: 'โบนัสแยกตามโปรโมชั่นที่ใช้',
                data: @php echo $customer_from_types->pluck('customers'); @endphp,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(2, 71, 181)',
                    'rgb(193, 253, 111)',
                    'rgb(172, 127, 203)',
                    'rgb(203, 53, 175)',
                    'rgb(226, 45, 44)',
                    'rgb(102, 181, 19)',
                    'rgb(92, 165, 221)',
                    'rgb(250, 40, 162)',
                    'rgb(250, 252, 120)',
                    'rgb(67, 59, 246)',
                    'rgb(10, 13, 111)',
                    'rgb(3, 31, 54)',
                    'rgb(28, 48, 98)',
                    'rgb(241, 42, 203)',
                    'rgb(196, 81, 184)',
                    'rgb(217, 246, 233)',
                    'rgb(27, 140, 62)',
                    'rgb(87, 229, 235)',
                    'rgb(227, 31, 114)',
                    'rgb(89, 38, 62)',
                ],
                hoverOffset: 4
            }]
        };
        const configDonut = {
            type: 'doughnut',
            data: dataDonut,
        };
        const labelDonut2 = @php echo $collect_bank_accounts; @endphp;
        const dataDonut2 = {
            labels: labelDonut2,
            datasets: [{
                label: 'โบนัสแยกตามโปรโมชั่นที่ใช้',
                data: @php echo $bank_account_transactions->pluck('count'); @endphp,
                backgroundColor: [
                    'rgb(196, 81, 184)',
                    'rgb(217, 246, 233)',
                    'rgb(27, 140, 62)',
                    'rgb(87, 229, 235)',
                ],
                hoverOffset: 4
            }]
        };
        const configDonut2 = {
            type: 'doughnut',
            data: dataDonut2,
        };
    </script>
    <script>
        const myChartDonut = new Chart(
            document.getElementById('myChartDonut'),
            configDonut
        );
        const myChartDonut2 = new Chart(
            document.getElementById('myChartDonut2'),
            configDonut2
        );
    </script>

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
