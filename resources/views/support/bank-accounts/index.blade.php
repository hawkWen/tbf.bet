@extends('layouts.support')

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

                                จัดการบัญชีธนาคาร</a>
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
                        data-target="#createBankAccountModal">
                        <i class="fa fa-plus"></i>เพิ่มบัญชีธนาคาร</a>
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
                            จัดการบัญชีธนาคาร
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
                                {{-- <td>เปิด/ปิด Statement</td> --}}
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

                                        @if ($bank_account->type == 0)
                                            <span class="text-success">เข้า/ออก (auto)</span>
                                        @elseif($bank_account->type == 1)
                                            <span class="text-success">ขาเข้า (auto)</span>
                                        @elseif($bank_account->type == 2)
                                            <span class="text-danger">ขาเข้าสำรอง (manual)</span>
                                        @elseif($bank_account->type == 3)
                                            <span class="text-warning">ขาออก (auto)</span>
                                        @elseif($bank_account->type == 4)
                                            <span class="text-primary">ขาออกสำรอง (manual)</span>
                                        @elseif($bank_account->type == 5)
                                            <span class="text-primary">กลาง</span>
                                        @elseif($bank_account->type == 6)
                                            <span class="text-primary">ขาเข้า SCB EASY</span>
                                        @elseif($bank_account->type == 7)
                                            <span class="text-danger">ขาออก SCB EASY</span>
                                        @elseif($bank_account->type == 8)
                                            <span class="text-danger">truemoney manual</span>
                                        @elseif($bank_account->type == 9)
                                            <span class="text-success">SCB PIN ขาเข้า</span>
                                        @elseif($bank_account->type == 10)
                                            <span class="text-danger">SCB PIN ขาออก</span>
                                        @elseif($bank_account->type == 11)
                                            <span class="text-danger">SCB PIN ขาเข้า/ขาออก</span>
                                        @endif
                                    </td>
                                    {{-- <td align="center">
                                        <input data-switch="true" type="checkbox" @if ($bank_account->status == 1) checked="checked" @endif
                                            id="status_{{ $bank_account->id }}" data-on-color="primary"
                                            onchange="updateStatus({{ $bank_account->id }},'status')" />
                                    </td> --}}
                                    <td align="center">
                                        <input data-switch="true" type="checkbox"
                                            @if ($bank_account->status_bot == 1) checked="checked" @endif
                                            id="status_bot_{{ $bank_account->id }}" data-on-color="primary"
                                            onchange="updateStatus({{ $bank_account->id }},'status_bot')" />
                                    </td>
                                    {{-- <td align="center">
                                        <input data-switch="true" type="checkbox" @if ($bank_account->status_transaction == 1) checked="checked" @endif
                                            id="status_transaction_{{ $bank_account->id }}" data-on-color="primary"
                                            onchange="updateStatus({{ $bank_account->id }},'status_transaction')" />
                                    </td> --}}
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
                                        <div class="modal fade" id="editBankAccountModal_{{ $bank_account->id }}"
                                            data-backdrop="static" tabindex="-1" role="dialog"
                                            aria-labelledby="staticBackdrop" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <form action="{{ route('support.bank-account.update') }}" method="post"
                                                    id="formUpdatebankAccount" enctype="multipart/form-data">
                                                    <input type="hidden" name="bank_account_id"
                                                        value="{{ $bank_account->id }}" />
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                แก้ไขบัญชีธนาคาร</h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <i aria-hidden="true" class="ki ki-close"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <label for="">แบรนด์</label>
                                                                    <select name="brand_id" id="brand_id"
                                                                        class="form-control">
                                                                        <option value="">เลือก</option>
                                                                        @foreach ($brands as $brand)
                                                                            <option value="{{ $brand->id }}"
                                                                                @if ($brand->id == $bank_account->brand_id) selected @endif>
                                                                                {{ $brand->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label for="">ธนาคาร</label>
                                                                    <select name="bank_id" id="bank_id"
                                                                        class="form-control">
                                                                        <option value="">เลือก</option>
                                                                        @foreach ($banks as $bank)
                                                                            <option value="{{ $bank->id }}"
                                                                                @if ($bank->id == $bank_account->bank_id) selected @endif>
                                                                                {{ $bank->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-8">
                                                                    <label for="">ชื่อบัญชี</label>
                                                                    <input type="text" class="form-control" name="name"
                                                                        value="{{ $bank_account->name }}">
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <label for="">เลขที่บัญชี</label>
                                                                    <input type="text" class="form-control" name="account"
                                                                        value="{{ $bank_account->account }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <label for="">Username (Kbank)</label>
                                                                    <input type="text" class="form-control"
                                                                        name="username"
                                                                        value="{{ $bank_account->username }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label for="">Password (Kbank)</label>
                                                                    <input type="text" class="form-control"
                                                                        name="password"
                                                                        value="{{ $bank_account->password }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <label for="">AppId (SCB)</label>
                                                                    <input type="password" class="form-control"
                                                                        name="app_id"
                                                                        value="{{ $bank_account->app_id }}">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label for="">Token (SCB)</label>
                                                                    <input type="password" class="form-control"
                                                                        name="token" value="{{ $bank_account->token }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-3 ml-auto">
                                                                    <label for="">Type</label>
                                                                    <select name="type" id="type" class="form-control">
                                                                        <option value="">เลือก</option>
                                                                        <option value="0"
                                                                            @if ($bank_account->type == 0) selected @endif>
                                                                            เข้า/ออก
                                                                            AUTO</option>
                                                                        <option value="1"
                                                                            @if ($bank_account->type == 1) selected @endif>
                                                                            ขาเข้า
                                                                            AUTO</option>
                                                                        <option value="2"
                                                                            @if ($bank_account->type == 2) selected @endif>
                                                                            ขาเข้า
                                                                            MANUAL</option>
                                                                        <option value="3"
                                                                            @if ($bank_account->type == 3) selected @endif>
                                                                            ขาออก
                                                                            AUTO</option>
                                                                        <option value="4"
                                                                            @if ($bank_account->type == 4) selected @endif>
                                                                            ขาออก
                                                                            MANUAL</option>
                                                                        <option value="5"
                                                                            @if ($bank_account->type == 5) selected @endif>
                                                                            บัญชีกลาง
                                                                        </option>
                                                                        <option value="9"
                                                                            @if ($bank_account->type == 9) selected @endif>
                                                                            SCB PIN
                                                                            ขาเข้า</option>
                                                                        <option value="10"
                                                                            @if ($bank_account->type == 10) selected @endif>
                                                                            SCB PIN
                                                                            ขาออก</option>
                                                                        <option value="11"
                                                                            @if ($bank_account->type == 11) selected @endif>
                                                                            SCB PIN
                                                                            ขาเข้า/ขาออก</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                class="btn btn-light-warning font-weight-bold"
                                                                data-dismiss="modal">ยกเลิก</button>
                                                            <button type="submit"
                                                                class="btn btn-warning font-weight-bold">บันทึก</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Modal-->
                                        <div class="modal fade" id="deleteBankAccountModal_{{ $bank_account->id }}"
                                            data-backdrop="static" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('support.bank-account.delete') }}" method="post">
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
            <div class="modal fade" id="createBankAccountModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <form action="{{ route('support.bank-account.store') }}" method="post" id="formBankAccountCreate">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มบัญชีธนาคาร</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="">แบรนด์</label>
                                        <select name="brand_id" id="brand_id" class="form-control">
                                            <option value="">เลือก</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">ธนาคาร</label>
                                        <select name="bank_id" id="bank_id" class="form-control">
                                            <option value="">เลือก</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="">ชื่อบัญชี</label>
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="">เลขที่บัญชี</label>
                                        <input type="text" class="form-control" name="account">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="">Username </label>
                                        <input type="text" class="form-control" name="username">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Password </label>
                                        <input type="text" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="">AppId (SCB)</label>
                                        <input type="text" class="form-control" name="app_id">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Token (SCB)</label>
                                        <input type="text" class="form-control" name="token">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">URL DATA</label>
                                        <input type="text" class="form-control" name="url_data">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 ml-auto">
                                        <label for="">Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">เลือก</option>
                                            <option value="0">เข้า/ออก AUTO</option>
                                            <option value="1">ขาเข้า AUTO</option>
                                            <option value="2">ขาเข้า MANUAL</option>
                                            <option value="3">ขาออก AUTO</option>
                                            <option value="4">ขาออก MANUAL</option>
                                            <option value="5">บัญชีกลาง</option>
                                            <option value="9">SCB PIN ขาเข้า</option>
                                            <option value="10">SCB PIN ขาออก</option>
                                            <option value="11">SCB PIN ขาเข้า/ขาออก</option>
                                        </select>
                                    </div>
                                </div>
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
    {!! JsValidator::formRequest('App\Http\Requests\BankAccountRequest', '#formBankAccountCreate') !!}

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

            $.post('{{ route('support.bank-account.update-status') }}', {
                bank_account_id: bank_account_id,
                type: type,
                status: status
            }, function() {

            });

        }
    </script>
@endsection
