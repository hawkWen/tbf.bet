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
                    <a href="#" class="btn btn-light-primary font-weight-bolder btn-sm" data-toggle="modal"
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
                            บอทเติมเงิน
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <hr>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="200">วันที่/เวลา</th>
                                <th>แบรนด์</th>
                                <th>ธนาคาร</th>
                                <th>เลขอ้างอิง</th>
                                <th>จำนวนเงิน</th>
                                <th>สถานะ</th>
                                {{-- <th>จัดการ</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bank_account_transactions as $bank_account_transaction)
                                <tr>
                                    <td>{{ $bank_account_transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $bank_account_transaction->brand->name }}</td>
                                    <td>
                                        <img src="{{ asset($bank_account_transaction->bankAccount->bank->logo) }}"
                                            width="20" class="img-fluid" alt="" width="20">
                                        {{ $bank_account_transaction->bankAccount->bank->name }}
                                        {{ $bank_account_transaction->bankAccount->name }}
                                    </td>
                                    <td>
                                        @if ($bank_account_transaction->code == 'X1')
                                            {{ $bank_account_transaction->code_bank }} /
                                            {{ $bank_account_transaction->bank_account }}
                                        @else
                                            {{ $bank_account_transaction->description }}
                                        @endif
                                    </td>
                                    <td align="right">
                                        {{ number_format($bank_account_transaction->amount, 2) }}
                                    </td>
                                    <td align="center">
                                        @if ($bank_account_transaction->status == 0)
                                            <span class="text-info">
                                                <i class="fas fa-robot mr-2"></i>
                                                รอบอทเติมเงิน
                                            </span>
                                        @elseif($bank_account_transaction->status == 1)
                                            <span class="text-success">
                                                <i class="fa fa-check mr-2"></i>
                                                เติมเงินเสร็จแล้ว
                                            </span>
                                            <span class="text-center">
                                                @if ($bank_account_transaction->deposit)
                                                    <p>
                                                        ลูกค้า:
                                                        {{ $bank_account_transaction->deposit->customer->username }}
                                                    </p>
                                                @endif
                                            </span>
                                        @elseif($bank_account_transaction->status == 2)
                                            <span class="text-warning mr-2">
                                                <i class="far fa-clock"></i>
                                                กำลังเชื่อมต่อ API
                                            </span>
                                        @elseif($bank_account_transaction->status == 3)
                                            @if ($bank_account_transaction->code == 'X1')
                                                <span class="text-danger mr-2">
                                                    <i class="fa fa-times"></i>
                                                    เบิ้ล
                                                </span>
                                            @else
                                                <span class="text-warning mr-2">
                                                    <i class="fa fa-credit-card"></i>
                                                    ยอดโอนออก
                                                </span>
                                            @endif
                                        @elseif($bank_account_transaction->status == 4)
                                            <span class="text-danger mr-2">
                                                <i class="fa fa-times"></i>
                                                ไม่พบบัญชีนี้ในระบบ
                                            </span>
                                        @elseif($bank_account_transaction->status == 5)
                                            <span class="text-warning mr-2">
                                                <i class="fa fa-times"></i>
                                                รายการนี้เติมมือแล้ว
                                            </span>
                                        @elseif($bank_account_transaction->status == 6)
                                            <span class="text-warning mr-2">
                                                <i class="fa fa-times"></i>
                                                ติดโปรโมชั่น
                                            </span>
                                        @elseif($bank_account_transaction->status == 8)
                                            <span class="text-warning mr-2">
                                                <i class="fa fa-times"></i>
                                                เลขที่บัญชี SCB 4 หลักซ้ำกัน
                                            </span>
                                        @elseif($bank_account_transaction->status == 9)
                                            <span class="text-danger mr-2">
                                                <i class="fa fa-times"></i>
                                                ลูกค้าออนไลน์อยู่
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary"
                                            data-target="#changeStatusModal_{{ $bank_account_transaction->id }}"
                                            data-toggle="modal">
                                            เปลี่ยนสถานะ
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal-->
                                <div class="modal fade" id="changeStatusModal_{{ $bank_account_transaction->id }}"
                                    data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('agent.report.bank-account-transaction.update') }}"
                                            method="post">
                                            <input type="hidden" name="bank_account_transaction_id"
                                                value="{{ $bank_account_transaction->id }}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">เปลี่ยนสถานะ</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="">สถานะ</label>
                                                            <select name="status" id="status" class="form-control">
                                                                <option value="0">บอทเติมใหม่</option>
                                                                <option value="1">บอทเติมเสร็จแล้ว</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold"
                                                        data-dismiss="modal">ยกเลิก</button>
                                                    <button type="submit"
                                                        class="btn btn-primary font-weight-bold">ยืนยัน</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">
                        {{ $bank_account_transactions->links() }}
                    </div>
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



    </script>
@endsection
