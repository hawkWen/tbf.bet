{{-- <!DOCTYPE html> --}}
<html lang="en">
<!--begin::Head-->

<head>
    <meta charset="utf-8" />
    <title>หน้าจัดการ</title>
    <meta name="description" content="" />

    @include('parts.support.css')

    @yield('css')

</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled page-loading">
    <!--begin::Main-->
    @include('parts.support.header')
    <!--end::Header Mobile-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">
            <!--begin::Aside-->
            <div class="aside aside-left d-flex flex-column" id="kt_aside">
                <!--begin::Brand-->
                <div class="aside-brand d-flex flex-column align-items-center flex-column-auto pt-5 pt-lg-18 pb-10">
                    <!--begin::Logo-->
                    <div class="btn p-0 symbol symbol-60 symbol-light-primary" href="metronic/demo6/index.html"
                        id="kt_quick_user_toggle">
                        <div class="symbol-label">
                            <img alt="Logo"
                                src="{{ asset('metronic/demo6/dist/assets/media/svg/avatars/001-boy.svg') }}"
                                class="h-75 align-self-end" />
                        </div>
                    </div>
                    <!--end::Logo-->
                </div>
                <!--end::Brand-->
                @include('parts.support.menu')
            </div>
            <!--end::Aside-->
            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header-->
                <!--end::Header-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="container-fluid mt-4">
                        <h3 class="text-dark">
                            {{ env('APP_NAME') }}
                        </h3>
                        <hr>
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
                                @if (Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} mb-0 text-white">
                                        {{ Session::get('alert-' . $msg) }}
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    @yield('content')

                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="footer py-2 py-lg-0 my-5 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div
                        class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted font-weight-bold mr-2">2020 ©</span>
                            <a href="" target="_blank" class="text-primary text-hover-primary">{{ env('APP_NAME') }}</a>
                        </div>
                        <!--end::Copyright-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->
    <!-- begin::User Panel-->
    <div id="kt_quick_user" class="offcanvas offcanvas-left p-10">
        <!--begin::Header-->
        <div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
            <h3 class="font-weight-bold m-0">User Profile
        </div>
        <!--end::Header-->
        <!--begin::Content-->
        <div class="offcanvas-content pr-5 mr-n5">
            <!--begin::Header-->
            <div class="d-flex align-items-center mt-5">
                <div class="d-flex flex-column">
                    <a href="#"
                        class="font-weight-bold font-size-h5 text-primary text-hover-primary">{{ Auth::user()->username }}</a>
                    <div class="text-muted mt-1">{{ Auth::user()->userRole->name }}</div>
                </div>
                <form id="logout-form" action="{{ route('support.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            <!--end::Header-->
            <!--begin::Separator-->
            <div class="separator separator-dashed mt-8 mb-5"></div>
            <!--end::Separator-->
            <!--begin::Nav-->
            <div class="navi navi-spacer-x-0 p-0">
                <!--begin::Item-->
                <a data-toggle="modal" data-target="#changePasswordModal" class="navi-item">
                    <div class="navi-link">
                        <div class="navi-text">
                            <div class="font-weight-bold">Change Password</div>
                            <div class="text-muted">เปลี่ยนรหัสผ่าน</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('support.logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();" class="navi-item">
                    <div class="navi-link">
                        <div class="navi-text">
                            <div class="font-weight-bold">Log Out</div>
                            <div class="text-muted">ออกจากระบบ</div>
                        </div>
                    </div>
                </a>
            </div>
            <!--end::Nav-->
        </div>
        <!--end::Content-->
    </div>
    <!-- Modal-->
    <div class="modal fade" id="changePasswordModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('support.change-password') }}" method="post" id="formChangePassword">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">เปลี่ยนรหัสผ่าน</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="">รหัสผ่านเดิม</label>
                                <input type="password" class="form-control" name="password_old">
                            </div>
                            <div class="col-lg-12">
                                <label for="">รหัสผ่านใหม่</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="col-lg-12">
                                <label for="">ยืนยันรหัสผ่านใหม่อีกครั้ง</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        <label for=""></label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">บันทึก</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('parts.support.javascript')

    @yield('javascript')

    {!! JsValidator::formRequest('App\Http\Requests\PasswordRequest', '#formChangePassword') !!}

</body>
<!--end::Body-->

</html>
