@extends('layouts.uking-auth')

@section('css')

@endsection

@section('content')
<!-- Begin page content -->
<main class="flex-shrink-0 main has-footer">
    
    <!-- Fixed navbar -->
        <header class="header">
            <div class="row">
                <div class="text-left col align-self-center">
                    <a href="{{route('uking.member', $brand->subdomain)}}" class="btn btn-link" type="button">
                        <i class="fa fa-sign-in-alt"></i> เข้าสู่ระบบ
                    </a>

                </div>
                <div class="ml-auto col-auto align-self-center">
                    <a href="{{route('uking.member', $brand->subdomain)}}" class="text-white">
                        <img src="{{$brand->logo_url}}" alt="" class="img-fluid rounded-circle" width="80">
                    </a>
                </div>
            </div>
        </header>
        <form action="{{route('uking.member.register.store', $brand->subdomain)}}" method="post" id="formCustomerCreate">
        <div class="container h-100 text-white" style="margin-top: 50px">
            <div class="row h-100" id="formBank">
                <div class="col-12 align-self-center mb-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            
                                <input type="hidden" name="brand_id" id="brandId" value="{{$brand->id}}" >
                                @if($customer_invite)
                                    <input type="hidden" name="invite_id" value="{{$customer_invite->id}}">
                                @else
                                    <input type="hidden" name="invite_id" value="0">
                                @endif
                                <input type="hidden" name="username" id="usernameInput">
                                <input type="hidden" name="password" id="passwordInput">
                                <h3>สมัครสมาชิกใหม่</h3>
                                <small class="font-weight-normal mb-5"> ระบุข้อมูลบัญชีธนาคารของคุณ</small>
                                <div class="form-group float-label mt-2">
                                    <select name="bank_id" id="bank_id" class="form-control form-login" style="color: white">
                                        <option value=""></option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->id}}:{{$bank->code}}">{{$bank->name}}</option>
                                        @endforeach
                                    </select>
                                    <label class="form-control-label text-white"> <i class="fa fa-universiy"></i> ธนาคาร</label>
                                </div>
                                <div class="form-group float-label position-relative">
                                    <input type="tel" name="bank_account" id="bank_account" class="form-control form-login text-white active">
                                    <label class="form-control-label text-white"> <i class="fa fa-credit-card"></i> เลขที่บัญชีธนาคาร</label>
                                </div>
                                <div class="form-group float-label position-relative">
                                    <input type="text" name="fname" id="fname" class="form-control form-login text-white active">
                                    <label class="form-control-label text-white"> <i class="fa fa-user"></i> ชื่อ</label>
                                </div>
                                <div class="form-group float-label position-relative">
                                    <input type="text" name="lname" id="lname" class="form-control form-login text-white active">
                                    <label class="form-control-label text-white"> <i class="fa fa-user"></i> นามสกุล</label>
                                </div>
                                <small class="text-center"> <i class="fa fa-info"></i> ชื่อบัญชีธนาคาร กับ ชื่อที่ใช้สมัครต้องตรงกัน</small>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row h-100" id="formGeneral" style="display: none;">
                <div class="col-12 align-self-center mb-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <p class="font-weight-normal mb-2"> ข้อมูลทั่วไป</p>
                            @if($brand->status_telephone == 1)
                                <div class="form-group float-label position-relative">
                                    <input type="tel" name="telephone" id="telephone" class="form-control form-login text-white active" required>
                                    <label class="form-control-label text-white"> <i class="fa fa-phone"></i> เบอร์โทรศัพท์</label>
                                </div>
                            @endif
                            @if($brand->status_line_id == 1)
                                <div class="form-group float-label position-relative">
                                    <input type="text" name="line_id" id="line_id" class="form-control form-login text-white active" required>
                                    <label class="form-control-label text-white"> <i class="fab fa-line"></i> LINE ID</label>
                                </div>
                            @endif
                            <div class="form-group float-label mt-2">
                                <select name="from_type_id" id="from_type_id" class="form-control form-login" style="color: white;">
                                    <option value=""></option>
                                    <option value="facebook">Facebook</option>
                                    <option value="line">LINE</option>
                                    <option value="google">Google</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="tiktok">Tiktok</option>
                                    <option value="sms">SMS</option>
                                    <option value="เพื่อน"  @if($customer_invite) selected @endif>เพื่อนแนะนำ</option>
                                    <option value="ads">โฆษณา</option>
                                    <option value="etcs.">อื่นๆ ระบุ</option>
                                </select>
                                <label class="form-control-label text-white"> <i class="fa fa-bullhorn"></i> รู้จักเราผ่านทาง</label>
                            </div>
                            <div class="form-group float-label position-relative">
                                <input type="text" name="from_type_id" id="from_type_id" class="form-control form-login text-white active">
                                <label class="form-control-label text-white"> <i class="fa fa-comment-dots"></i> เพิ่มเติม</label>
                            </div>
                            <small class="text-center"> <i class="fa fa-info"></i> ชื่อบัญชีธนาคาร กับ ชื่อที่ใช้สมัครต้องตรงกัน</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col" id="btnNext">
                    <button type="button" onclick="checkBank()" id="btnBank" class="btn btn-primary rounded btn-block">
                        <span id="btnBankLoading" style="display: none;">
                            <span class="spinner-grow spinner-grow-sm mr-2" id="btnBankLoading" role="status" aria-hidden="true"></span>
                            กำลังตรวจสอบข้อมูล ...
                        </span>
                        <span id="btnBankTxt">
                            <i class="fa fa-chevron-right"></i>
                            ถัดไป
                        </span>
                    </button>
                </div>
                <div class="col" id="btnRegister" style="display: none;">
                    <button type="button" onclick="back();" class="btn btn-primary rounded btn-block">
                        <i class="fa fa-chevron-left"></i>
                        ก่อนหน้า</button>
                    <button type="submit" class="btn btn-success rounded btn-block">
                        <i class="fa fa-check"></i>
                        ยืนยันการสมัคร</button>
                </div>
            </div>
        </div>
    </main>

    <!-- footer-->
    <div class="footer no-bg-shadow py-3">
    </form>
</div>
@endsection

@section('javascript')

{{-- {!! JsValidator::formRequest('App\Http\Requests\MemberRegisterRequest','#formCustomerCreate') !!} --}}

<script>

    $(function() {
        $('#formCustomerCreate').preventDoubleSubmission();
    });

    function checkBank() {

        var fname = $('#fname').val();

        var lname = $('#lname').val();

        var bankId = $('#bank_id').val();

        var bank_account = $('#bank_account').val();

        var brand_id = $('#brandId').val();

        if(bankId == '') {
            $('#bank_id').notify("กรุณาระบุธนาคาร");
            return ;
        }

        if(bank_account == '' || bank_account.length < 10) {
            $('#bank_account').notify('กรุณาระบุเลขที่บัญชี 10 หลัก');
            return ;
        }

        if(fname == '') {
            
            $('#fname').notify("กรุณาระบุชื่อ");
            return;
        }

        if(lname == '') {
            $('#lname').notify("กรุณาระบุชื่อ");
            return ;
        }

        $('#btnBank').html('<span id="btnBankLoading"><span class="spinner-grow spinner-grow-sm mr-2" id="btnBankLoading" role="status" aria-hidden="true"></span>กำลังตรวจสอบข้อมูล ...</span>');
        $('#btnBank').attr('disabled', true);

        $.post('{{route('uking.member.check-bank')}}', {brand_id: brand_id,bank_account: bank_account, bank_id: bankId}, function(r) {
            if(r.count === false) {
                $('#bank_account').notify('เลขที่บัญชีธนาคารนี้ มีในระบบแล้ว กรุณาติดต่อเจ้าหน้าที่');
                $('#btnBank').html('<i class="fa fa-chevron-right"></i>  ถัดไป');
                $('#btnBank').attr('disabled', false);
                return ;
            } else {
                $('#username_').html(r.username);
                $('#usernameInput').val(r.username);
                $('#password_').html(r.password);
                $('#passwordInput').val(r.password);
                $('#formGeneral').fadeIn('fast');
                $('#formBank').fadeOut('fast');
                $('#btnNext').hide();
                $('#btnRegister').show();
                $('#btnBank').html('<span id="btnBankLoading"><span class="spinner-grow spinner-grow-sm mr-2" id="btnBankLoading" role="status" aria-hidden="true"></span>กำลังตรวจสอบข้อมูล ...</span>');
                $('#btnBank').attr('disabled', true);
            }
        }); 

    }

    function back() {

        $('#formGeneral').fadeOut('fast');
        $('#formBank').fadeIn('fast');
        $('#btnNext').show();
        $('#btnRegister').hide();
        $('#btnBank').html('<i class="fa fa-chevron-right"></i>  ถัดไป');
        $('#btnBank').attr('disabled', false);

    }

</script>
    
@endsection