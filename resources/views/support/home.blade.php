@extends('layouts.support')

@section('css')
@endsection

@section('content')

    <div class="content d-flex flex-column flex-column-fluid">
        <div class="d-flex flex-column-fluid">
            {{-- @if (Auth::user()->user_role_id != 3) --}}
            <div class="container-fluid">
                <p class="text-dark ">ภาพรวมประจำวัน {{ $dates['input_start_date'] }} -
                    {{ $dates['input_end_date'] }}
                </p>
                <hr>
                <form action="{{ route('support') }}" method="get">
                    <div class="row">
                        <div class="col-lg-2">
                            <label for="">เลือกแบรนด์</label>
                            <select name="brand_id" id="brand_id" class="form-control" required>
                                <option value="">เลือกแบรนด์</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" @if ($brand_select == $brand->id) selected @endif>
                                        {{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
                @if ($brand_select != 0)
                    <div class="row mt-0 mt-lg-8">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <!--begin::Mixed Widget 4-->
                                    <div class="card card-custom card-stretch card-shadowless gutter-b">
                                        <!--begin::Header-->
                                        <div class="card-header border-0 py-5">

                                            <h3 class="card-title font-weight-bolder text-info">ภาพรวม

                                            </h3>
                                            <h3 class="card-title font-weight-bolder text-info">
                                                จำนวนใบงานทั้งหมด
                                                {{ number_format($customer_deposits->count() + $customer_withdraws->count()) }}
                                            </h3>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Body-->
                                        <div class="card-body d-flex flex-column p-0">
                                            <!--begin::Chart-->
                                            <!--end::Chart-->
                                            <!--begin::Stats-->
                                            <div class="card-spacer bg-white card-rounded flex-grow-1">
                                                <!--begin::Row-->
                                                <div class="row m-0">
                                                    <div class="col px-8 py-6 mr-8">
                                                        <div class="font-size-sm text-dark font-weight-bold">ยอดเติมเงิน
                                                        </div>
                                                        <div class="font-size-h4 font-weight-bolder text-success">$
                                                            {{ number_format($customer_deposits->sum('amount'), 2) }}
                                                        </div>
                                                        <small
                                                            class="text-success">{{ number_format($customer_deposits->count()) }}
                                                            ครั้ง</small>
                                                    </div>
                                                    <div class="col px-8 py-6">
                                                        <div class="font-size-sm text-dark font-weight-bold">ยอดถอนเงิน
                                                        </div>
                                                        <div class="font-size-h4 font-weight-bolder text-danger">$
                                                            {{ number_format($customer_withdraws->sum('amount'), 2) }}
                                                        </div>
                                                        <small
                                                            class="text-success">{{ number_format($customer_withdraws->count()) }}
                                                            ครั้ง</small>
                                                    </div>
                                                </div>
                                                <!--end::Row-->
                                                <!--begin::Row-->
                                                <div class="row m-0">
                                                    <div class="col px-8 py-6 mr-8">
                                                        <div class="font-size-sm text-dark font-weight-bold">ลูกค้าใหม่
                                                        </div>
                                                        <div class="font-size-h4 font-weight-bolder">
                                                            {{ number_format($customer_news->count()) }} /
                                                            {{ number_format($customers->count()) }}</div>
                                                    </div>
                                                    <div class="col px-8 py-6">
                                                        <div class="font-size-sm font-weight-bold">
                                                            ลูกค้าที่มีการเติมเงินในวันนี้</div>
                                                        <div class="font-size-h4 font-weight-bolder text-info"> <i
                                                                class="fa fa-users text-info"></i>
                                                            {{ $customer_active->count() }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Body-->
                                    </div>
                                    <!--end::Mixed Widget 4-->
                                </div>
                                <div class="col-lg-12">
                                    <!--begin::Mixed Widget 4-->
                                    <div class="card card-custom card-stretch card-shadowless gutter-b">
                                        <!--begin::Header-->
                                        <div class="card-header border-0 py-5">
                                            <h3 class="card-title font-weight-bolder text-warning">โปรโมชั่นที่ใช้วันนี้
                                            </h3>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Body-->
                                        <div class="card-body d-flex flex-column p-4">
                                            <table class="table table-head-custom table-vertical-center">
                                                <thead>
                                                    <tr class="text-left">
                                                        <td>โปรโมชั่น</td>
                                                        <td align="right">เครดิตที่ใช้ไป</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($group_by_promotion_costs as $group_by_promotion_cost)
                                                        @php
                                                            $promotion = App\Models\Promotion::find($group_by_promotion_cost->promotion_id);
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                @if ($group_by_promotion_cost->promotion)
                                                                    {{ $group_by_promotion_cost->promotion->name }}
                                                                @else
                                                                    โบนัสวงล้อ
                                                                @endif
                                                            </td>
                                                            <td align="right">
                                                                {{ number_format($group_by_promotion_cost->bonus, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2" align="right">
                                                            โปรโมชั่นทั่วไป $
                                                            {{ number_format($promotion_costs->where('promotion.type_promotion', '!=', 6)->sum('bonus'), 2) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" align="right">
                                                            เครดิตฟรี $
                                                            {{ number_format($promotion_costs->where('promotion.type_promotion', '=', 6)->sum('bonus'), 2) }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" align="right">
                                                            ยอดรวม $
                                                            {{ number_format($promotion_costs->sum('bonus'), 2) }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <!--end::Body-->
                                    </div>
                                    <!--end::Mixed Widget 4-->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-4">
                                    <div class="card card-custom card-stretch gutter-b">
                                        <!--begin::Header-->
                                        <div class="card-header border-0">
                                            <h3 class="card-title font-weight-bolder text-success">ลูกค้าที่เติมเงินสูงสุด 5
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
                                                            <td>{{ $customer_deposit_top->customer->name }}</td>
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
                                            <h3 class="card-title font-weight-bolder text-danger">ลูกค้าที่ถอนเงินสูงสุด 5
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
                                                            <td>{{ $customer_withdraw_top->customer->name }}</td>
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
                                                ลูกค้าที่รับโปรโมชั่สูงสุด 5
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
                                                            <td>{{ $customer_promotion_top->customer->name }}</td>
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
                            <div class="card card-custom card-stretch card-shadowless gutter-b"
                                style="height: 500px;overflow: scroll;">
                                <!--begin::Header-->
                                <div class="card-header border-0 py-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label font-weight-bolder text-dark">รายการธนาคารของฉัน</span>
                                        <span class="text-muted mt-3 font-weight-bold font-size-sm"></span>
                                    </h3>
                                    <div class="card-toolbar">
                                    </div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-0">
                                    @foreach ($bank_accounts->sortByDesc('type') as $bank_account)
                                        <div class="d-flex align-items-center mb-9 bg-light-success rounded p-5">
                                            <!--begin::Icon-->
                                            @if ($bank_account->bank)
                                                <img src="{{ asset($bank_account->bank->logo) }}" width="50"
                                                    class="mr-5 img-rounded" alt="">
                                                <!--end::Icon-->
                                            @endif

                                            <!--begin::Title-->
                                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                                <a href="#"
                                                    class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">
                                                    @if ($bank_account->bank)
                                                        {{ $bank_account->bank->name }}
                                                    @endif
                                                    <span>
                                                        @if ($bank_account->type == 1)
                                                            <span class="text-success">ขาเข้า (auto)</span>
                                                        @elseif($bank_account->type == 2)
                                                            <span class="text-danger">ขาเข้าสำรอง (manual)</span>
                                                        @elseif($bank_account->type == 3)
                                                            <span class="text-warning">ขาออก (auto)</span>
                                                        @elseif($bank_account->type == 4)
                                                            <span class="text-primary">ขาออกสำรอง (manual)</span>
                                                        @elseif($bank_account->type == 5)
                                                            <span class="text-primary">กลาง</span>
                                                        @endif
                                                    </span>
                                                </a>
                                                <span class="text-dark font-weight-bold">{{ $bank_account->name }}
                                                    {{ $bank_account->account }}</span>
                                            </div>
                                            <!--end::Title-->

                                            <!--begin::Lable-->
                                            <span
                                                class="font-weight-bolder text-warning py-1 font-size-lg">{{ number_format($bank_account->amount, 2) }}</span>
                                            <!--end::Lable-->
                                            <br>
                                            @if ($bank_account->type == 0 || $bank_account->type == 1 || $bank_account->type == 3 || $bank_account->type == 9 || $bank_account->type == 10 || $bank_account->type == 11)
                                                @if ($bank_account->bank_id != 0)
                                                    <a href="{{ route('agent.bank-account.update-amount-bot', $bank_account->id) }}"
                                                        class="btn btn-primary btn-xs ml-3 pull-right" data-toggle="tooltip"
                                                        data-placement="top" title="อัพเดทเงินในัญชี">
                                                        <i class="fa fa-sync"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <!--end::Body-->
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            {{-- @endif --}}
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
