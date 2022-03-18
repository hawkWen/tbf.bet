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
                    <form action="{{ route('agent.marketing.top') }}" method="get">
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
                        <div class="col-lg-3">
                            <div class="card card-custom gutter-b">
                                <!--begin::Body-->
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                                        <span class="symbol symbol-50 symbol-light-success mr-2">
                                            <span class="symbol-label">
                                                <i class="fa fa-dollar-sign"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column text-right">
                                            <span class="text-success font-weight-bolder font-size-h3"> +
                                                {{ number_format($customer_deposits->sum('total'), 2) }}
                                                $</span>
                                            <span class="text-muted font-weight-bold mt-2">ยอดเติมเงิน</span>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card card-custom gutter-b">
                                <!--begin::Body-->
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                                        <span class="symbol symbol-50 symbol-light-danger mr-2">
                                            <span class="symbol-label">
                                                <i class="fa fa-credit-card"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column text-right">
                                            <span class="text-danger font-weight-bolder font-size-h3"> -
                                                {{ number_format($customer_withdraws->sum('total'), 2) }}
                                                $</span>
                                            <span class="text-muted font-weight-bold mt-2">ยอดถอนเงิน</span>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card card-custom gutter-b">
                                <!--begin::Body-->
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                                        <span class="symbol symbol-50 symbol-light-warning mr-2">
                                            <span class="symbol-label">
                                                <i class="fa fa-tags"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column text-right">
                                            <span class="text-warning font-weight-bolder font-size-h3">
                                                {{ number_format($promotion_costs->sum('total'), 2) }}
                                                $</span>
                                            <span class="text-muted font-weight-bold mt-2">โบนัสที่ใช้</span>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card card-custom gutter-b">
                                <!--begin::Body-->
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                                        <span class="symbol symbol-50 symbol-light-primary mr-2">
                                            <span class="symbol-label">
                                                <i class="fa fa-users"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column text-right">
                                            <span class="text-dark-75 font-weight-bolder font-size-h3">
                                                {{ number_format($customer_news->count()) }} /
                                                {{ number_format($customers->count()) }}</span>
                                            <span class="text-muted font-weight-bold mt-2">ลูกค้าใหม่</span>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">

                        <div class="card-body">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">

                    <div class="card">

                        <div class="card-body">
                            <canvas id="myChartDonut"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">
                    <div class="card card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bolder text-success">ลูกค้าที่เติมเงินสูงสุด 10
                                อันดับ</h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td>ลูกค้า</td>
                                        <td>จำนวนเงินที่เติม</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_deposit_tops as $customer_deposit_top)
                                        <tr>
                                            <td>{{ $customer_deposit_top->customer->name }}
                                                ({{ $customer_deposit_top->customer->username }})
                                            </td>
                                            <td align="right">
                                                {{ number_format($customer_deposit_top->total_deposit, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <div class="col-4">
                    <div class="card card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bolder text-danger">ลูกค้าที่ถอนเงินสูงสุด 10
                                อันดับ</h3>
                        </div>
                        <!--end::Header-->
                        <div class="card-body pt-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td>ลูกค้า</td>
                                        <td>จำนวนเงินที่ถอน</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_withdraw_tops as $customer_withdraw_top)
                                        <tr>
                                            <td>{{ $customer_withdraw_top->customer->name }}
                                                ({{ $customer_withdraw_top->customer->username }})
                                            </td>
                                            <td align="right">
                                                {{ number_format($customer_withdraw_top->total_withdraw, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <div class="col-4">
                    <div class="card card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bolder text-warning">
                                ลูกค้าที่รับโปรโมชั่สูงสุด 10
                                อันดับ</h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td>ลูกค้า</td>
                                        <td>จำนวนโบนัสที่ได้</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_promotion_tops as $customer_promotion_top)
                                        <tr>
                                            <td>{{ $customer_promotion_top->customer->name }}
                                                ({{ $customer_promotion_top->customer->username }})
                                            </td>
                                            <td align="right">
                                                {{ number_format($customer_promotion_top->total_bonus, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
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
        var query_date = $('#labels').val();
        console.log(query_date);
        const labels = @php echo $query_date; @endphp;
        const data = {
            labels: labels,
            datasets: [{
                label: 'ยอดเติมเงิน',
                backgroundColor: 'rgb(14 128 235)',
                borderColor: 'rgb(14 128 235)',
                data: @php echo $customer_deposits->pluck('total') @endphp,
            }, {
                label: 'ยอดถอนเงิน',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: @php echo $customer_withdraws->pluck('total') @endphp,
            }, {
                label: 'ยอดค่าใช้จ่ายโบนัส',
                backgroundColor: 'rgb(255,165,0)',
                borderColor: 'rgb(255,165,0)',
                data: @php echo $promotion_costs->pluck('total') @endphp,
            }]
            //  {
            //     label: 'My First dataset',
            //     backgroundColor: 'rgb(255 168 0)',
            //     borderColor: 'rgb(255 168 0)',
            //     data: [9, 10, 5, 2, 20, 17, 22],
            // }]
        };
        const config = {
            type: 'bar',
            data: data,
            options: {}
        };
        const labelDonut = @php echo $query_promotion; @endphp;
        const dataDonut = {
            labels: labelDonut,
            datasets: [{
                label: 'โบนัสแยกตามโปรโมชั่นที่ใช้',
                data: @php echo $promotion_cost_tops->pluck('total_bonus'); @endphp,
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
    </script>
    <script>
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
        const myChartDonut = new Chart(
            document.getElementById('myChartDonut'),
            configDonut
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
