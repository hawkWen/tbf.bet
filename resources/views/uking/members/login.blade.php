@extends('layouts.uking-auth')

@section('css')
    
@endsection

@section('content')

<form action="{{route('uking.member.login.store', $brand->subdomain)}}" method="post" id="formLogin">
    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <header class="header">
            <div class="row">
                <div class="ml-auto col-auto align-self-center">
                    <a href="{{route('uking.member', $brand->subdomain)}}" class="text-white">
                        <img src="{{$brand->logo_url}}" alt="" class="img-fluid rounded-circle" width="100">
                    </a>
                </div>
            </div>
        </header>

        <div class="container h-100 text-white">
            <div class="container">
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
            </div>
            <div class="row h-100">
                <div class="col-12 align-self-center mb-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <h2 class="font-weight-normal mt-5">เข้าสู่ระบบ {{$brand->name}}</h2>
                            <div class="form-group float-label active">
                                <input type="text" name="username" class="form-control form-login text-white" >
                                <label class="form-control-label text-white">ไอดีเข้าเล่นเกมส์</label>
                                <small>{{$brand->agent_username}} ตามด้วย เลขที่บัญชี 6 หลักสุดท้าย"</small>
                            </div>
                            <div class="form-group float-label">
                                <input type="password" name="password" class="form-control form-login text-white " >
                                <label class="form-control-label text-white">รหัสผ่าน</label>
                            </div>  
                            <p class="text-right">
                                @php
                                    $line_add = '@'.$brand->line_id;
                                @endphp
                                <a href="https://line.me/R/ti/p/~{{$line_add}}" class="pull-right text-white"> <i class="fab fa-line"></i> ลืมรหัสผ่าน</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col">
                    <button type="submit" class="btn btn-default rounded btn-block">
                        <i class="fa fa-sign-in-alt"></i>    
                        เข้าสู่ระบบ</button>
                </div>
                <div class="col">
                    <a href="{{route('uking.member.register', ['brand' => $brand->subdomain, 'invite_id' => substr(str_shuffle(str_repeat($x='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10)])}}" class="btn btn-primary rounded btn-block text-white">
                        <i class="fa fa-user-plus"></i>   สมัครสมาชิก</a>
                </div>
            </div>
        </div>
            
    </main>
    <!-- footer-->
    <div class="footer no-bg-shadow py-3">
    </div>
</form>
    
@endsection

@section('javascript')
    
@endsection