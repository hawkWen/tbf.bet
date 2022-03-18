<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta charset="utf-8" />
    <title>{{ env('APP_NAME') }}</title>
    <meta name="description" content="ระบบเอเย่นต์คาสิโน" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{{ asset('metronic/demo6/dist/assets/css/pages/login/classic/login-3.css?v=7.0.6') }}"
        rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{ asset('metronic/demo6/dist/assets/plugins/global/plugins.bundle.css?v=7.0.6') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('metronic/demo6/dist/assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.6') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/demo6/dist/assets/css/style.bundle.css?v=7.0.6') }}" rel="stylesheet"
        type="text/css" />
    <!--end::Global Theme Styles-->

    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-3 login-signin-on d-flex flex-row-fluid" id="kt_login">
            <div class="d-flex flex-center bgi-size-cover bgi-no-repeat flex-row-fluid" style="background-color: white;">
                <div class="login-form text-center text-white p-7 position-relative overflow-hidden">
                    <!--begin::Login Header-->
                    <div class="d-flex flex-center">
                        <div class="d-flex flex-center">
                            @if (env('APP_NAME') === 'fast-x')
                                <a href="#">
                                    <img src="{{ asset('images/fastX.png') }}" class="max-h-100px" alt="" />
                                </a>
                            @else
                                <a href="#">
                                    <img src="{{ asset('images/logo.png') }}" class="max-h-100px" alt="" />
                                </a>
                            @endif
                        </div>
                    </div>
                    <!--end::Login Header-->

                    <!--begin::Login Sign in form-->
                    <div class="login-signin">
                        <div class="form-group">
                            <div class="mt-4">
                                @if ($errors->any())
                                    <div class="flash-message">
                                        @foreach ($errors->all() as $error)
                                            <p class="alert alert-danger float-left">{{ $error }}
                                            </p>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flash-message">
                                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                        @if (Session::has('alert-' . $msg))
                                            <p class="alert alert-{{ $msg }} ">
                                                {{ Session::get('alert-' . $msg) }}
                                            </p>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        <div class="card ">
                            <div class="card-header">
                                <h3 class="text-dark">เชื่อมธนาคารกับบอท</h3>
                                <span class="text-dark"> <i class="fa fa-info-circle"></i>
                                    กรอกข้อมูลธนาคารให้ถูกต้อง
                                    และ รอยืนยัน OTP ค่ะ</span>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    {{-- <div class="form-group-prepend">
                                        <span class="form-group-text text-light" id="basic-addon1"
                                            style="width: 130px; background: rgba(0,0,0,0.4) !important;">
                                            เลขบัตรปรชาชน</span>
                                    </div> --}}
                                    <label for="" style="float: left !important">เลขที่บัญชีธนาคาร</label>
                                    <input type="text" class="form-control" name="bankAccount" id="bankAccount"
                                        value="" placeholder="199323XXXX">
                                </div>
                                <div class="form-group mb-3">
                                    {{-- <div class="form-group-prepend">
                                        <span class="form-group-text text-light" id="basic-addon1"
                                            style="width: 130px; background: rgba(0,0,0,0.4) !important;">
                                            เลขบัตรปรชาชน</span>
                                    </div> --}}
                                    <label for="" style="float: left !important">ชื่อบัญชีธนาคาร</label>
                                    <input type="text" class="form-control" name="name" id="name" value=""
                                        placeholder="ชื่อบัญชี">
                                </div>
                                <div class="form-group mb-3">
                                    {{-- <div class="form-group-prepend">
                                        <span class="form-group-text text-light" id="basic-addon1"
                                            style="width: 130px; background: rgba(0,0,0,0.4) !important;">
                                            เลขบัตรปรชาชน</span>
                                    </div> --}}
                                    <label for="" style="float: left !important">เลขบัตรประชาชน</label>
                                    <input type="text" class="form-control" name="cardId" id="cardId" value=""
                                        placeholder="1460500228031">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="" style="float: left !important">ปี - เดือน - วัน</label>
                                    <input type="text" class="form-control mt-2" name="dateOfBirth" id="dateOfBirth"
                                        value="" placeholder="1995-08-21">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="" style="float: left !important">เบอร์โทรศัพท์ที่ผูกกับบัญชี</label>
                                    <input type="text" class="form-control mt-2" name="MobilePhoneNo" id="MobilePhoneNo"
                                        value="" placeholder="080xxxxx">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="" style="float: left !important">หมายเหตุเพิ่มเติม</label>
                                    <input type="text" class="form-control mt-2" name="remark" id="remark" value=""
                                        placeholder="แบรนด์ , ประเภทบัญชี (ขาเข้า , ขาออก , ขาเข้า + ขาออก)">
                                </div>
                                <button type="button" id="btnGetFlag" class="btn btn-success btn-block"
                                    onclick="get_otp()">ถัดไป</button>
                            </div>

                        </div>
                    </div>
                    <!--end::Login Sign in form-->
                </div>
            </div>
        </div>
        <!--end::Login-->
    </div>
    <!--end::Main-->


    <!-- The Modal -->
    <div class="modal fade in" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">ยืนยัน OTP</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-light" id="basic-addon1"
                                style="width: 100px; background: rgba(0,0,0,0.4) !important;"> Ref</span>
                        </div>
                        <input type="text" class="form-control mt-2" name="dateOfBirth" id="Ref" placeholder="5qref"
                            disabled>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-light" id="basic-addon1"
                                style="width: 100px; background: rgba(0,0,0,0.4) !important;"> Otp</span>
                        </div>
                        <input type="text" class="form-control mt-2" name="dateOfBirth" id="Otp" placeholder="xxxxx">
                    </div>


                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-light" id="basic-addon1"
                                style="width: 100px; background: rgba(0,0,0,0.4) !important;"> Pin</span>
                        </div>
                        <input type="text" class="form-control mt-2" id="pin" placeholder="xxxxxx">
                    </div>


                    <input type="hidden" class="form-control mt-2" id="tokenUUID">
                    <input type="hidden" class="form-control mt-2" id="Auth">

                    <input type="hidden" class="form-control mt-2" id="deviceId">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="confirm_otp()"
                        id="btnConfirmOtp">ยืนยัน</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>

            </div>
        </div>
    </div>

    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="{{ asset('metronic/demo6/dist/assets/plugins/global/plugins.bundle.js?v=7.0.6') }}"></script>
    <script src="{{ asset('metronic/demo6/dist/assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.6') }}"></script>
    {{-- <script src="{{ asset('metronic/demo6/dist/assets/js/scripts.bundle.js?v=7.0.6') }}"></script> --}}
    <!--end::Global Theme Bundle-->

    <!--begin::Page Scripts(used by this page)-->
    {{-- <script src="{{asset('metronic/demo6/dist/assets/js/pages/custom/login/login-general.js?v=7.0.6')}}"></script> --}}
    <!--end::Page Scripts-->
</body>
<!--end::Body-->




<script type="text/javascript">
    window.onload = function() {
        $.ajax({
            url: '{{ route('agent.bot.getflag') }}',
            method: "get",
            success: function(data) {
                if (data != "") {
                    var obj = JSON.parse(data);
                    var msg = obj.msg
                    var status = obj.status
                    if (status == 200) {
                        localStorage.setItem("deviceId", msg);

                        var deviceId = localStorage.getItem("deviceId");

                    }
                }

            }


        });
    }


    function get_otp() {
        var cardId = $("#cardId").val();
        var dateOfBirth = $("#dateOfBirth").val();
        var MobilePhoneNo = $("#MobilePhoneNo").val();
        var deviceId = localStorage.getItem("deviceId");
        var name = $('#name').val();
        var remark = $('#remark').val();
        var bankAccount = $('#bankAccount').val();
        if (cardId == '') {
            Swal.fire({
                icon: 'error',
                title: 'แจ้งเตือน...',
                text: 'ใส่เลขบัตรประชาชน'

            })

            return false

        }
        if (name == '') {
            Swal.fire({
                icon: 'error',
                title: 'แจ้งเตือน...',
                text: 'กรุณาใส่ชื่อบัญชี'

            })

            return false

        }
        if (bankAccount == '') {
            Swal.fire({
                icon: 'error',
                title: 'แจ้งเตือน...',
                text: 'กรุณาใส่เลขที่บัญชี'

            })

            return false

        }
        if (dateOfBirth == '') {

            Swal.fire({
                icon: 'error',
                title: 'แจ้งเตือน...',
                text: 'ใส่วันเกิด'

            })

            return false
        }

        if (MobilePhoneNo == '') {
            Swal.fire({
                icon: 'error',
                title: 'แจ้งเตือน...',
                text: 'ใส่เบอร์โทร'

            })

            return false

        }
        $('#btnGetFlag').attr('disabled', true);
        $.ajax({
            url: '{{ route('agent.bot.register') }}',
            method: "post",
            data: {
                cardId: cardId,
                dateOfBirth: dateOfBirth,
                MobilePhoneNo: MobilePhoneNo,
                bankAccount: bankAccount,
                name: name,
            },
            success: function(data) {
                if (data != "") {
                    var obj = JSON.parse(data);
                    var msg = obj.msg
                    var status = obj.status
                    var ref = obj.ref
                    var Auth = obj.Auth
                    if (status == 200) {
                        document.getElementById("Ref").value = ref;
                        document.getElementById("tokenUUID").value = msg;
                        document.getElementById("Auth").value = Auth;
                        document.getElementById("deviceId").value = deviceId;
                        $("#myModal").modal();
                        $('#btnGetFlag').attr('disabled', false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'แจ้งเตือน...',
                            text: msg

                        });
                        $('#btnGetFlag').attr('disabled', false);
                    }
                }

            }

        });
    }

    function confirm_otp() {

        var Otp = $("#Otp").val();
        var pin = $("#pin").val();
        var tokenUUID = $("#tokenUUID").val();
        var Auth = $("#Auth").val();
        var deviceId = $("#deviceId").val();
        var MobilePhoneNo = $("#MobilePhoneNo").val();
        if (Otp == '') {
            Swal.fire({
                icon: 'error',
                title: 'แจ้งเตือน...',
                text: 'ใส Otp'

            })

            return false

        }
        if (pin == '') {

            Swal.fire({
                icon: 'error',
                title: 'แจ้งเตือน...',
                text: 'ใส่ pin'

            })

            return false
        }

        $('#btnConfirmOtp').attr('disabled', true);

        $.ajax({
            url: '{{ route('agent.bot.cf-otp') }}',
            method: "post",
            data: {
                Otp: Otp,
                pin: pin,
                tokenUUID: tokenUUID,
                Auth: Auth,
                deviceId: deviceId,
                MobilePhoneNo: MobilePhoneNo
            },
            success: function(data) {
                if (data != "") {
                    var obj = JSON.parse(data);
                    var msg = obj.msg
                    var status = obj.status

                    if (status == 200) {

                        Swal.fire({
                            title: 'สำเร็จ!',
                            text: msg,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.value) {
                                window.location.reload();
                            }
                        });

                        $('#btnConfirmOtp').attr('disabled', false);


                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'แจ้งเตือน...',
                            text: msg

                        })
                        $('#btnConfirmOtp').attr('disabled', false);
                    }
                }

            }

        });
    }
</script>

</html>
