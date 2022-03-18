@extends('layouts.frontend3-auth')

@section('css')

@endsection

@section('content')


<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="auth-wrapper auth-v1 px-2">
                <div class="auth-inner py-2" >
                    <div class="flash-message">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }} mb-2">
                                    {{ Session::get('alert-' . $msg) }}
                                </p>
                            @endif
                        @endforeach
                    </div>
                    <!-- Register v1 -->
                    <form action="{{route('fastbet.member.register.store', $brand->subdomain)}}" method="post" id="formCustomerCreate">
                        <input type="hidden" name="brand_id" id="brandId" value="{{$brand->id}}" >
                        @if($customer_invite)
                            <input type="hidden" name="invite_id" value="{{$customer_invite->id}}">
                        @else
                            <input type="hidden" name="invite_id" value="0">
                        @endif
                        <div class="card mb-0" >
                            <div class="card-body card-register">
                                <br>
                                <img src="{{$brand->logo_url}}" width="125" class="img-center rounded-circle" alt="">
                                <a href="javascript:void(0);" class="brand-logo">
                                </a>
                                <h5 class="card-title mb-0">ยินดีต้อนรับสมาชิกทุกท่าน </h5>
                                <p class="card-text">ฝาก/ถอน ออโต้ 3 วินาทีเท่านั้น 🚀</p>
                                <hr>
                                <div class="tab">
                                    <div class="row">
                                        <div class="col-4">
                                            <button class="btn btn-secondary btn-block btn-active" id="stepPhone">
                                                <i class="fa fa-phone"></i>
                                            </button>
                                        </div>
                                        <hr>
                                        <div class="col-4">
                                            <button class="btn btn-secondary btn-block" id="stepBank">
                                                <i class="fa fa-credit-card"></i>
                                            </button>
                                        </div>
                                        <hr>
                                        <div class="col-4">
                                            <button class="btn btn-secondary btn-block" id="stepInfo">
                                                <i class="fa fa-info-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div id="divTelephone">
                                    <div class="form-group">
                                        <label for="" class="form-label">เบอร์โทรศัพท์</label>
                                        <input type="tel" class="form-control mb-1" id="register-telephone" name="telephone" placeholder="080-0000000" autofocus/>
                                        <p><div class="badge badge-glow badge-info text-dark"> <i class="fa fa-info"></i> ต้องระบุเบอรโทรศัพท์ที่ใช้ได้จริง </div></p>
                                        <p><div class="badge badge-glow badge-info text-dark"> <i class="fa fa-info"></i> ใช้ในกรณีโอนเงิน truewallet </div></p>
                                        <div class="pull-right">
                                        </div>
                                    </div>
                                </div>
                                <div id="divBank" style="display: none;">
                                    <div class="form-group">
                                        <label for="" class="form-label">ธนาาคาร</label>
                                        <select name="bank_id" id="register-bank-id" class="form-control" >
                                            <option value="">เลือกธนาคาร</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->id}}:{{$bank->code}}">{{$bank->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">เลขที่บัญชี</label>
                                        <input type="tel" class="form-control" id="register-bank-account" name="bank_account"  autofocus/>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">ชื่อ </label> <span class="text-muted pull-right"><div class="badge badge-glow badge-info text-dark"> <i class="fa fa-info"></i> ไม่ต้องใส่คำนำหน้า</div></span>
                                        <input type="text" class="form-control" id="register-fname" name="fname"  autofocus />
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">นามสกุล</label>
                                        <input type="text" class="form-control" id="register-lname" name="lname"  autofocus />
                                    </div>
                                    <div class="badge badge-glow badge-danger"> <i class="fa fa-exclamation-circle"></i> ชื่อที่สมัครกับชื่อในเลขที่บัญชีธนาคาร ต้องตรงกันเท่านั้น </div>
                                    <div class="badge badge-glow badge-danger"> <i class="fa fa-exclamation-circle"></i> มิเช่นนั้นระบบจะไม่เติมเงินให้คุณ </div>
                                </div>
                                <div id="divInfo" style="display: none;">
                                    <div class="form-group">
                                        <label for="register-password" class="form-label">รหัสผ่าน <span class="text-muted pull-right"><div class="badge badge-glow badge-info text-dark"> <i class="fa fa-info"></i> ระบุ 6 ตัวขึ้นไป</div></label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input
                                                type="text"
                                                class="form-control form-control-merge"
                                                id="register-password"
                                                name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="register-password"
                                                tabindex="3"
                                                minlength="6"
                                                required
                                            />
                                            <div class="input-group-append">
                                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">LINE ID</label>
                                        <input type="text" class="form-control" id="" name="line_id"  autofocus required/>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">รู้จักเราผ่านทาง </label> 
                                        <select name="from_type" id="from_type" class="form-control" required>
                                            <option value="">เลือก</option>
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
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">อื่นๆ ระบุ</label>
                                        <input type="text" class="form-control" id="" name="from_type_remark"  autofocus/>
                                    </div>
                                </div>
                                <div class="btn-footer">
                                    <div id="btnPhone">
                                        <button type="button" class="btn btn-primary" onclick="nextBank()">
                                            
                                            ถัดไป
                                            <i class="fa fa-caret-right"></i>
                                        </button>
                                    </div>
                                    <div id="btnBank" style="display: none;">
                                        <button type="button" class="btn btn-info" onclick="backPhone()">
                                            <i class="fa fa-caret-left"></i>
                                            ก่อนหน้า
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="nextInfo()">
                                            
                                            ถัดไป
                                            <i class="fa fa-caret-right"></i>
                                        </button>
                                    </div>
                                    <div id="btnInfo" style="display: none;">
                                        <button type="button" class="btn btn-info" onclick="backBank()">
                                            <i class="fa fa-caret-left"></i>
                                            ก่อนหน้า
                                        </button>
                                        <button type="submit" href="register-success.html" class="btn btn-primary" onclick="nextInfo()">
                                            <i class="fa fa-check"></i>
                                            ยืนยันการสมัคร
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="text-right">
                        เป็นสมาชิกอยู่แล้ว ? 
                        <a href="{{route('fastbet.member', $brand->subdomain)}}" class="" type="button">
                            เข้าสู่ระบบ
                        </a>
                    </div>
                    <p class="text-center pt-1" style="color: #82868b;">Powered By Casinoauto.io</p>
                </div>
            </div>
            <hr>
        </div>
    </div>
</div>
@endsection

@section('javascript')

{{-- {!! JsValidator::formRequest('App\Http\Requests\MemberRegisterRequest','#formCustomerCreate') !!} --}}

<script>

    $(function() {
        $('#formCustomerCreate').preventDoubleSubmission();
        $('#register-telephone').inputmask('999-9999999', {"placeholder": ""});   
        $('#register-bank-account').inputmask('999999999999999', {"placeholder": ""});
    });

    function backPhone() {

        $('#divTelephone').show();
        $('#divBank').hide();
        $('#divInfo').hide();

        $('#btnPhone').show();
        $('#btnBank').hide();
        $('#btnInfo').hide();

        $('#stepPhone').addClass('btn-active');
        $('#stepBank').removeClass('btn-active');
        $('#stepInfo').removeClass('btn-active');
        
    }

    function nextBank() {

        //check phone length
        var brand_id = $('#brandId').val();
        var telephone = $('#register-telephone').val();
        if(telephone.length < 11) {
            toastr['warning']('กรุณาระบุเบอร์โทรศัพท์ 10 หลักนะคะ', 'คำเตือน', {
                closeButton: true,
                tapToDismiss: false,
                timeOut: 3000,
            });
            return ;
        }

        //check phone unique
        $.post('{{route('fastbet.member.check-phone')}}', {telephone: telephone, brand_id: brand_id}, function(r) {
            if(r.count > 0) {
                toastr['error']('เบอร์โทรศัพท์นี้มีในระบบแล้วกรุณาติดต่อเจ้าหน้าที่', 'คำเตือน', {
                    closeButton: true,
                    tapToDismiss: false,
                    timeOut: 3000,
                });
                return ;
            } else {
                $('#divBank').show();
                $('#divTelephone').hide();
                $('#divInfo').hide();

                $('#btnPhone').hide();
                $('#btnBank').show();
                $('#btnInfo').hide();

                $('#stepPhone').removeClass('btn-active');
                $('#stepBank').addClass('btn-active');
                $('#stepInfo').removeClass('btn-active');
            }
        });

    }

    function backBank() {

        $('#divBank').show();
        $('#divTelephone').hide();
        $('#divInfo').hide();

        $('#btnPhone').hide();
        $('#btnBank').show();
        $('#btnInfo').hide();

        $('#stepPhone').removeClass('btn-active');
        $('#stepBank').addClass('btn-active');
        $('#stepInfo').removeClass('btn-active');

    }

    function nextInfo() {

        var brand_id = $('#brandId').val();
        var bank_id = $('#register-bank-id').val();
        var fname = $('#register-fname').val();
        var lname = $('#register-lname').val();
        var bank_account = $('#register-bank-account').val();
        if(bank_id == '') {
            toastr['warning']('กรุณาระบุธนาคาร', 'คำเตือน', {
                closeButton: true,
                tapToDismiss: false,
                timeOut: 3000,
            });
            return ;
        }
        if(bank_account.length < 10) {
            toastr['warning']('กรุณาระบุเลขที่บัญชี 10 หลักนะคะ', 'คำเตือน', {
                closeButton: true,
                tapToDismiss: false,
                timeOut: 3000,
            });
            return ;
        }
        if(fname == '') {
            toastr['warning']('กรุณาระบุชื่อ', 'คำเตือน', {
                closeButton: true,
                tapToDismiss: false,
                timeOut: 3000,
            });
            return ;
        }
        if(lname == '') {
            toastr['warning']('กรุณาระบุนามสกุล', 'คำเตือน', {
                closeButton: true,
                tapToDismiss: false,
                timeOut: 3000,
            });
            return ;
        }

        $.post('{{route('fastbet.member.check-bank')}}', {bank_id: bank_id,bank_account: bank_account, brand_id: brand_id}, function(r) {
            // alert(r);
            if(r.count === false) {
                toastr['error']('เลขที่บัญชีนี้มีในระบบแล้วกรุณาติดต่อเจ้าหน้าที่', 'คำเตือน', {
                    closeButton: true,
                    tapToDismiss: false,
                    timeOut: 3000,
                });
                return ;
            } else {
                $('#divBank').hide();
                $('#divTelephone').hide();
                $('#divInfo').show();

                $('#btnPhone').hide();
                $('#btnBank').hide();
                $('#btnInfo').show();

                $('#stepPhone').removeClass('btn-active');
                $('#stepBank').removeClass('btn-active');
                $('#stepInfo').addClass('btn-active');
            }
        });

    }

</script>
    
@endsection