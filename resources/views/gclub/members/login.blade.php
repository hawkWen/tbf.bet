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
                    <!-- Register v1 -->
                    <form action="{{route('gclub.member.login.store', $brand->subdomain)}}" method="post" id="formLogin">
                        <div class="card mb-0" >
                            <div class="card-body card-login">
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
                                <br>
                                <img src="{{$brand->logo_url}}" width="125" class="img-center img-fluid rounded-circle" alt="">
                                <a href="javascript:void(0);" class="brand-logo">
                                </a>
                                <h5 class="card-title mb-0">ยินดีต้อนรับสมาชิกทุกท่าน 🚀 </h5>
                                <hr>
                                <div id="divTelephone">
                                    <div class="form-group">
                                        <label for="register-telephone" class="form-label">ไอดีเข้าเล่นเกมส์</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="{{$brand->agent_prefix}}" autofocus/>
                                        @if($brand->status_telephone == 1)
                                            <small> <i class="fa fa-info"></i> ไอดีของผู้เล่นจะนำด้วย {{$brand->agent_prefix}} ตามด้วยเบอร์โทรศัพท์ 6 ตัวท้าย</small>
                                        @else
                                            <small> <i class="fa fa-info"></i> ไอดีของผู้เล่นจะนำด้วย {{$brand->agent_prefix}} ตามด้วยเลขที่บัญชี 6 ตัวท้าย</small>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="register-password" class="form-label">รหัสผ่าน</label>
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input
                                                type="password"
                                                class="form-control form-control-merge"
                                                id="register-password"
                                                name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password"
                                                tabindex="3"
                                                minlength="6"
                                            />
                                            <div class="input-group-append">
                                                <span class="input-group-text cursor-pointer"><i class="fa fa-eye"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{route('gclub.member.register', ['brand' => $brand->subdomain, 'invite_id' => substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10)])}}" class="btn btn-info text-dark" onclick="nextBank();"> <i class='fa fa-user-plus'></i> สมัครสมาชิก</a>
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary text-dark" onclick="nextBank();"> <i class='fa fa-sign-in-alt'></i> เข้าสู่ระบบ</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="pull-right">
                        
                        ลืมรหัสผ่าน ? 
                    @php
                        $line_add = '@'.$brand->line_id;
                    @endphp
                    <a href="https://line.me/R/ti/p/~{{$line_add}}" target="_blank">แจ้งเจ้าหน้าที่</a>
                    </div>
                    <div class="cleafix"></div>
                    <br>
                    <p class="text-center pt-1" style="color: #82868b;">Powered By Casinoauto.io</p>
                </div>
            </div>
            <hr>
        </div>
    </div>
</div>
    
@endsection

@section('javascript')

    <script>

        $(function() {
            $('#register-telephone').inputmask('999-9999999')
        });

    </script>
    
@endsection