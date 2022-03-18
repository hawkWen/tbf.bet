@extends('layouts.admin')

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

                                บัญชีทรูมันนี่</a>
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
                        data-target="#createBankAccount">
                        <i class="fa fa-plus"></i>เพิ่มบัญชีทรูมันนี่</a>
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
                            บัญชีทรูมันนี่
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td width="100">ลำดับที่</td>
                                <td>แบรนด์</td>
                                <td>ธนาคาร</td>
                                <td>ชื่อบัญชี</td>
                                <td>เลขที่บัญชี</td>
                                <td>ประเภท</td>
                                {{-- <td>เปิด/ปิดใช้งาน</td> --}}
                                <td>เปิด/ปิด Bot</td>
                                <td>จัดการ</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bank_accounts as $key_bank_account => $bank_account)
                                <tr>
                                    <td>{{ $key_bank_account + 1 }}</td>
                                    <td>
                                        @if ($bank_account->brand)
                                            {{ $bank_account->brand->name }}
                                        @endif
                                    </td>
                                    <td>
                                        <img src="{{ asset($bank_account->bank->logo) }}" alt="" width="30">
                                        {{ $bank_account->bank->name }}
                                    </td>
                                    <td>
                                        {{ $bank_account->name }}
                                    </td>
                                    <td>
                                        {{ $bank_account->account }}
                                    </td>
                                    <td>
                                        ทรูมันนี่ auto
                                    </td>
                                    {{-- <td align="center">
                                        <input data-switch="true" type="checkbox"
                                            @if ($bank_account->status == 1) checked="checked" @endif
                                            id="status_{{ $bank_account->id }}" data-on-color="primary"
                                            onchange="updateStatus({{ $bank_account->id }},'status')" />
                                    </td> --}}
                                    <td align="center">
                                        <input data-switch="true" type="checkbox"
                                            @if ($bank_account->status_bot == 1) checked="checked" @endif
                                            id="status_bot_{{ $bank_account->id }}" data-on-color="primary"
                                            onchange="updateStatus({{ $bank_account->id }},'status_bot')" />
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#editBankAccountModal_{{ $bank_account->id }}">
                                            แก้ไข
                                        </button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#deleteBankAccountModal_{{ $bank_account->id }}">
                                            ลบ
                                        </button>
                                        <!-- Modal-->
                                        <div class="modal fade" id="deleteBankAccountModal_{{ $bank_account->id }}"
                                            data-backdrop="static" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.bank-account.delete') }}" method="post">
                                                    <input type="hidden" name="bank_account_id"
                                                        value="{{ $bank_account->id }}">
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
            <div class="modal  fade" id="createBankAccount" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <form action="{{ route('admin.truemoney.store') }}" id="formTruemoney" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">เพิ่มบัญชีทรูมันนี่</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        แบรนด์
                                        <select class="form-control" name="brand_id">
                                            <option value="">เลือก</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        ชื่อ-นามสกุล
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                    <div class="col-lg-4">
                                        เบอร์โทรศัพท์
                                        <input type="number" class="form-control" name="account">
                                    </div>
                                    <div class="col-lg-4">
                                        Pin
                                        <input type="text" class="form-control" name="pin">
                                    </div>
                                    {{-- <div class="col-lg-6">
                                    Username
                                    <input type="text" class="form-control" name="username">
                                </div>
                                <div class="col-lg-6">
                                    Password
                                    <input type="text" class="form-control" name="password">
                                </div> --}}
                                    <div class="col-lg-4">
                                        Key ID
                                        <input type="text" class="form-control" name="tmn_one_id">
                                    </div>
                                    <div class="col-lg-4">
                                        login_token
                                        <input type="text" class="form-control" name="token">
                                    </div>
                                    <div class="col-lg-4">
                                        tmn_id
                                        <input type="text" class="form-control" name="app_id">
                                    </div>
                                </div>
                                <span class="badge badge-info">key_id , login_token , tmn_id ที่ได้จาก
                                    https://tmn.one</span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
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
    {!! JsValidator::formRequest('App\Http\Requests\TruemoneyRequest', '#formTruemoney') !!}
    <script>
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


        function updateStatus(bank_account_id, type) {

            var status = ($('#' + type + '_' + bank_account_id).is(':checked')) ? 1 : 0;

            $.post('{{ route('admin.bank-account.update-status') }}', {
                bank_account_id: bank_account_id,
                type: type,
                status: status
            }, function() {

            });

        }
    </script>
@endsection
