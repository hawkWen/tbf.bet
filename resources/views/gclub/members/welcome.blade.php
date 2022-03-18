@extends('layouts.frontend3-auth')

@section('css')

@endsection

@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="auth-wrapper auth-v1 px-2">
                <div class="auth-inner py-2">
                    <!-- Register v1 -->
                    <form class="auth-register-form mt-2" action="index.html" method="POST"></form>
                        <div class="card mb-0" >
                            <div class="card-body">
                                <img src="{{$brand->logo_url}}" width="125" class="img-fluid img-center rounded-circle" alt="">
                                <a href="javascript:void(0);" class="brand-logo">
                                </a>
                                <h5 class="card-title mb-0">สมัครสมาชิกระบบอัตโนมัติ 🚀 </h5>
                                <p class="card-text">ฝาก/ถอน ออโต้ 3 วินาทีเท่านั้น</p>
                                <hr>
                                <div class=""></div>
                                <div class="card card-congratulations bg-primary">
                                    <div class="card-body ">
                                        <div class="text-center">
                                            <img src="{{asset('frontend3/app-assets/images/elements/decore-left.png')}}" class="congratulations-img-left" alt="card-img-left" />
                                            <img src="{{asset('frontend3/app-assets/images/elements/decore-right.png')}}" class="congratulations-img-right" alt="card-img-right" />
                                            <div class="avatar avatar-xl bg-primary shadow ">
                                                <div class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award font-large-1">
                                                        <circle cx="12" cy="8" r="7"></circle>
                                                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <h3 class="mb-1 text-white">สมัครสมาชิกสำเร็จ</h3>
                                            <p class="card-text w-75">{{$customer->name}}</p>
                                            <p class="card-text w-75"><b>ไอดี</b>: {{$customer->username}}</p>
                                            <p class="card-text w-75"><b>รหัสผ่าน</b>: {{$customer->password_generate_2}}</p>
                                        </div>
                                        <hr >
                                        <div class="pull-right">
                                            <a href="{{route('gclub.member', $brand->subdomain)}}" class="btn btn-primary btn-block">
                                                <i class="fa fa-sign-in-alt"></i> &nbsp;
                                                เข้าสู่หน้าสมาชิก
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Register v1 -->
                    </form> 
                    <p class="text-center pt-1" style="color: #82868b;">Powered By Casinoauto.io</p>
                </div>
                
            </div>
            <hr>
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@section('javascript')

{{-- {!! JsValidator::formRequest('App\Http\Requests\MemberRegisterRequest','#formCustomerCreate') !!} --}}

<script>

    

</script>
    
@endsection