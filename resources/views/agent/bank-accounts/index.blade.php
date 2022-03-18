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
                    <a href="#" class="btn btn-primary font-weight-bolder btn-sm mr-2" data-toggle="modal"
                        data-target="#createBankAccountModal">
                        <i class="fa fa-plus"></i>เพิ่มบัญชีธนาคาร</a>
                    <a href="#" class="btn btn-danger font-weight-bolder btn-sm" data-toggle="modal"
                        data-target="#createTruemoveAccountModal">
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
                            จัดการบัญชีธนาคาร
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="pull-right" style="float: right">

                        <p class="text-right">CODE SMS: {{ $brand->code_sms }}</p>
                        <a href="https://bot.casinoauto.io/catnip1.1.apk" class="btn btn-primary">
                            <i class="fa fa-download"></i> ดาวน์โหลด APP OTP
                        </a>

                        <span class="switch switch-outline switch-icon switch-success pt-5 pb-2">
                            <label class="pt-2">
                                <input type="checkbox" id="status_brand_bot" value="1"
                                    onchange="updateStatusBot({{ $brand->id }})"
                                    @if ($brand->status_bot_deposit == 1) checked @endif />
                                <span></span>
                                เปิด/ปิด บอท เติมเงิน
                            </label>
                        </span>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td width="100">ลำดับที่</td>
                                <td>ธนาคาร</td>
                                <td>ชื่อบัญชี</td>
                                <td>เลขที่บัญชี</td>
                                <td>ประเภท</td>
                                <td>เงินใบบัญชี</td>
                                <td width="100">เปิด/ปิด บอท</td>
                                <td width="100">แสดงหน้าเว็บ</td>
                                <td>จัดการ</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bank_accounts as $key_bank_account => $bank_account)
                                <tr>
                                    <td>{{ $key_bank_account + 1 }}</td>
                                    <td>
                                        <img src="{{ asset($bank_account->bank->logo) }}" alt="" width="30">
                                        {{ $bank_account->bank->name }}
                                    </td>
                                    <td>
                                        {{ $bank_account->name }}
                                    </td>
                                    <td>
                                        {{ $bank_account->account }}
                                        @if ($bank_account->type == 6 || $bank_account->Type == 7)
                                            {{-- <p>
                                            @if (Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4)
                                                <small>Username: {{$bank_account->username}}</small>
                                                <small>Password: {{$bank_account->password}}</small>
                                            @endif
                                        </p> --}}
                                        @endif
                                    </td>
                                    <td align="center">
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
                                            @if ($bank_account->bank_id == 0)
                                                <span class="text-warning">TRUEMONEY AUTO</span>
                                            @else
                                                <span class="text-success">SCB PIN ขาเข้า</span>
                                            @endif
                                        @elseif($bank_account->type == 10)
                                            <span class="text-danger">SCB PIN ขาออก</span>
                                        @elseif($bank_account->type == 11)
                                            <span class="text-danger">SCB PIN ขาเข้า/ขาออก</span>
                                        @endif

                                    </td>
                                    <td align="center">
                                        {{ number_format($bank_account->amount, 2) }}
                                        @if (Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4)
                                            <a type="button" class="pull-right" data-toggle="modal"
                                                data-target="#updateAmountModal_{{ $bank_account->id }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if ($bank_account->type == 1 || $bank_account->type == 3 || $bank_account->type == 0 || $bank_account->type == 6 || $bank_account->type == 7 || $bank_account->type == 8 || $bank_account->type == 9 || $bank_account->type == 10 || $bank_account->type == 11)
                                            <span class="switch switch-outline switch-icon switch-success">
                                                <label>
                                                    <input type="checkbox" id="status_bot_{{ $bank_account->id }}"
                                                        value="1"
                                                        onchange="updateStatus({{ $bank_account->id }},'status_bot')"
                                                        @if ($bank_account->status_bot == 1) checked @endif />
                                                    <span></span>
                                                </label>
                                            </span>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <span class="switch switch-outline switch-icon switch-success">
                                            <label>
                                                <input type="checkbox" id="status_web_{{ $bank_account->id }}" value="1"
                                                    onchange="updateStatus({{ $bank_account->id }},'status_web')"
                                                    @if ($bank_account->status_web == 1) checked @endif />
                                                <span></span>
                                            </label>
                                        </span>
                                    </td>
                                    <td width="200" align="center">
                                        @if ($bank_account->type == 4 || $bank_account->type == 2 || $bank_account->bank_id == 5)
                                            @if ($bank_account->status_bot == 0)
                                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                                    data-target="#editBankAccountModal_{{ $bank_account->id }}">
                                                    แก้ไข
                                                </button>
                                            @endif
                                        @endif
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#deleteBankAccountModal_{{ $bank_account->id }}">
                                            ลบ
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal-->
                                <div class="modal fade" id="editBankAccountModal_{{ $bank_account->id }}"
                                    data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <form action="{{ route('agent.bank-account.update') }}" method="post"
                                            id="formUpdatebankAccount" enctype="multipart/form-data">
                                            <input type="hidden" name="bank_account_id"
                                                value="{{ $bank_account->id }}" />
                                            <input type="hidden" name="brand_id" value="{{ Auth::user()->brand_id }}" />
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-white" id="exampleModalLabel">
                                                        แก้ไขบัญชีธนาคาร</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="">Type</label>
                                                            <select name="type" id="type_{{ $bank_account->id }}"
                                                                class="form-control"
                                                                onchange="changeType({{ $bank_account->id }})">
                                                                <option value="">เลือก</option>
                                                                <option value="2"
                                                                    @if ($bank_account->type == 2) selected @endif>
                                                                    ขาเข้าสำรอง
                                                                    (manual)
                                                                </option>
                                                                <option value="4"
                                                                    @if ($bank_account->type == 4) selected @endif>
                                                                    ขาออกสำรอง
                                                                    (manual)</option>
                                                                <option value="5"
                                                                    @if ($bank_account->type == 5) selected @endif>
                                                                    บัญชีกลาง
                                                                </option>
                                                                {{-- <option value="6" @if ($bank_account->type == 6) selected @endif>ขาเข้า SCB EASY
                                                                </option>
                                                                <option value="7" @if ($bank_account->type == 7) selected @endif>ขาออก SCB EASY
                                                                    SMS</option> --}}
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">ธนาคาร</label>
                                                            <select @if ($bank_account->bank_id == 5) name="bank_id" @endif
                                                                id="bank_id_bot_{{ $bank_account->id }}"
                                                                @if ($bank_account->bank_id != 5) style="display:none;" @endif
                                                                class="form-control"
                                                                onclick="changeBank({{ $bank_account->id }})">
                                                                <option value="">เลือก</option>
                                                                @foreach ($banks->whereIn('id', [5]) as $bank)
                                                                    <option value="{{ $bank->id }}" selected>
                                                                        {{ $bank->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <select @if ($bank_account->bank_id != 5) name="bank_id" @endif
                                                                id="bank_id_manual_{{ $bank_account->id }}"
                                                                @if ($bank_account->bank_id == 5) style="display:none;" @endif
                                                                class="form-control"
                                                                onclick="changeBank({{ $bank_account->id }})">
                                                                <option value="">เลือก</option>
                                                                @foreach ($banks as $bank)
                                                                    <option value="{{ $bank->id }}">
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
                                                    <div class="row mt-2 " id="div_username_{{ $bank_account->id }}"
                                                        @if ($bank_account->bank_id != 5) style="display: none;" @endif>
                                                        <div class="col-lg-6">
                                                            <label for="">ไอดีเข้าหน้าเว็บ krungsrionline & k-cyber</label>
                                                            <input type="text" class="form-control" name="username"
                                                                value="{{ $bank_account->username }}" />
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="">รหัสผ่านเข้าเว็บ krungsrionline & k-cyber</label>
                                                            <input type="text" class="form-control" name="password"
                                                                value="{{ $bank_account->password }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-warning font-weight-bold"
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
                                    data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('agent.bank-account.delete') }}" method="post">
                                            <input type="hidden" name="bank_account_id" value="{{ $bank_account->id }}">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title text-white" id="exampleModalLabel">ยืนยันการลบ ?
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-danger font-weight-bold"
                                                        data-dismiss="modal">ยกเลิก</button>
                                                    <button type="submit"
                                                        class="btn btn-danger font-weight-bold">ยืนยัน</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Modal-->
                                <div class="modal fade" id="updateAmountModal_{{ $bank_account->id }}"
                                    data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('agent.bank-account.update-amount') }}" method="post">
                                            <input type="hidden" name="bank_account_id" value="{{ $bank_account->id }}">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-white" id="exampleModalLabel">
                                                        อัพเดทเงินตั้งต้น</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="">เงินตั้งต้น</label>
                                                            <input type="text" class="form-control"
                                                                input-type="money_decimal" name="amount"
                                                                value="{{ $bank_account->amount }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-danger font-weight-bold"
                                                        data-dismiss="modal">ยกเลิก</button>
                                                    <button type="submit"
                                                        class="btn btn-danger font-weight-bold">ยืนยัน</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
                    <form action="{{ route('agent.bank-account.store') }}" method="post" id="formBankAccountCreate">
                        <input type="hidden" name="brand_id" value="{{ Auth::user()->brand_id }}" />
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
                                        <label for="">Type</label>
                                        <select name="type" id="type_0" class="form-control" onchange="changeType(0)">
                                            <option value="">เลือก</option>
                                            <option value="2">ขาเข้าสำรอง (manual)</option>
                                            <option value="4">ขาออกสำรอง (manual)</option>
                                            <option value="5">บัญชีกลาง</option>
                                            {{-- <option value="6">ขาเข้า SCB EASY</option>
                                            <option value="7">ขาออก SCB EASY SMS</option> --}}
                                        </select>
                                    </div>
                                    <div class="col-lg-6 ">
                                        <label for="">ธนาคาร</label>
                                        <select name="bank_id" id="bank_id_bot_0" style="display: none;"
                                            class="form-control" onclick="changeBank(0)">
                                            <option value="">เลือก</option>
                                            @foreach ($banks->whereIn('id', [1]) as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="bank_id" id="bank_id_manual_0" class="form-control"
                                            onclick="changeBank(0)">
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
                                <div class="row mt-2 " id="div_username_0" style="display: none;">
                                    <div class="col-lg-6">
                                        <label for="">ไอดีเข้าหน้าเว็บ SCBEASY</label>
                                        <input type="text" class="form-control" name="username" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">รหัสผ่านเข้าเว็บ SCBEASY</label>
                                        <input type="text" class="form-control" name="password" />
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
            <!-- Modal-->
            <div class="modal fade" id="createTruemoveAccountModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <form action="{{ route('agent.bank-account.store') }}" method="post" id="formBankAccountCreate">
                        <input type="hidden" name="brand_id" value="{{ Auth::user()->brand_id }}" />
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มบัญชีทรูมันนี่</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h2 class="text-center">กรุณาติดต่อเจ้าหน้าที่เพื่อเชื่อมทรูมันนี่</h2>
                                {{-- <div class="row">
                                    <div class="col-lg-6">
                                        <label for="">Type</label>
                                        <select name="type" id="type_0" class="form-control" onchange="changeType(0)">
                                            <option value="">เลือก</option>
                                            <option value="8">ขาเข้า truemoney</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 ">
                                        <label for="">ธนาคาร</label>
                                        <select name="bank_id" id="bank_id" class="form-control">
                                            <option value="0">truemoney</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="">ชื่อบัญชี</label>
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="">เบอร์โทรศัพท์</label>
                                        <input type="text" class="form-control" name="username">
                                    </div>
                                </div>
                                <div class="row mt-2 " id="div_username_0">
                                    <div class="col-lg-6">
                                        <label for="">รหัสผ่าน</label>
                                        <input type="text" class="form-control" name="password" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">PIN 6 ตัว</label>
                                        <input type="text" class="form-control" name="pin" />
                                    </div>
                                </div> --}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">ตกลง</button>
                                {{-- <button type="submit" class="btn btn-primary font-weight-bold">เพิ่ม</button> --}}
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

        function changeType(attr_id) {

            var type = $('#type_' + attr_id).val();

            if (type == 7 || type == 6) {

                $('#bank_id_bot_' + attr_id).attr('name', 'bank_id');
                $('#bank_id_bot_' + attr_id).show();
                $('#bank_id_manual_' + attr_id).removeAttr('name');
                $('#bank_id_manual_' + attr_id).hide();
                $('#div_username_' + attr_id).show();

            } else if (type == 8) {

            } else {

                $('#bank_id_bot_' + attr_id).removeAttr('name');
                $('#bank_id_bot_' + attr_id).hide();
                $('#bank_id_manual_' + attr_id).attr('name', 'bank_id');
                $('#bank_id_manual_' + attr_id).show();
                $('#div_username_' + attr_id).hide();

            }

        }

        function updateStatus(bank_account_id, type) {

            var status = ($('#' + type + '_' + bank_account_id).is(':checked')) ? 1 : 0;

            $.post('{{ route('agent.bank-account.update-status') }}', {
                bank_account_id: bank_account_id,
                type: type,
                status: status
            }, function() {

            });

        }

        function updateStatusBot(brand_id) {

            var status = ($('#status_brand_bot').is(':checked')) ? 1 : 0;

            $.post('{{ route('agent.bank-account.update-status-bot') }}', {
                brand_id: brand_id,
                status: status
            }, function() {

            });

        }
    </script>
@endsection
