@php

$annoucement = App\Models\Annoucement::orderBy('created_at', 'desc')->first();

$notifications = App\Helpers\Helper::notification($brand->id);

// dd($notifications);

@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>หน้าจัดการ | {{ $brand->name }}</title>
    <meta name="description" content="" />

    @include('parts.agent.css')

    @yield('css')

</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled page-loading">
    <div class="annoucement">
        <marquee direction="lefe" scrollamount="5"> {{ $annoucement->title }} :: {{ $annoucement->content }}
        </marquee>
    </div>
    <!--begin::Main-->
    @include('parts.agent.header')
    <!--end::Header Mobile-->
    <div class="d-flex flex-column flex-root mt-5">
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
                @include('parts.agent.menu')
            </div>
            <!--end::Aside-->
            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header-->
                <!--end::Header-->
                <div class="content d-flex flex-column flex-column-fluid pt-3" id="kt_content">
                    <div class="container-fluid mt-4">
                        <h3 class="text-dark">
                            @if (env('APP_ENV') == 'production')
                                <img src="{{ $brand->logo_url }}" width="50" alt=""> &nbsp;
                            @else
                                <img src="{{ asset($brand->logo_url) }}" width="50" alt=""> &nbsp;
                            @endif
                            {{ strtoupper($brand->name) }}
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
                    <!-- Default dropup button -->
                    <!-- Default dropup button -->
                    <div class="dropdown dropdown-inline" data-toggle="tooltip" title="" data-placement="left"
                        data-original-title="More links">
                        <a href="#"
                            class="btn btn-icon btn-primary btn-notification btn-extra-lg btn-circle ml-3 flex-shrink-0"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="bell">
                            <i class="fa fa-bell fa-2x"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0" style="width: 500px"
                            id="divNotification">
                            <!--begin::Navigation-->
                            <ul class="navi navi-hover">
                                <li class="navi-header font-weight-bold py-4">
                                    <span class="font-size-lg">การแจ้งเตือน</span>
                                    <i class="flaticon2-information icon-md text-muted"></i>
                                </li>

                                @if ($notifications->count() > 0)
                                    @foreach ($notifications->sortByDesc('created_at') as $notification)
                                        <li class="navi-item">
                                            <span
                                                class="navi-link @if ($notification['type'] == 1) bg-warning @else bg-info @endif">
                                                <span class="navi-text ">
                                                    {{ $notification['message'] }}
                                                </span>
                                            </span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="navi-item">
                                        <span class="navi-link">
                                            <span class="navi-text text-center">
                                                ไม่พบการแจ้งเตือน
                                            </span>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                            <!--end::Navigation-->
                        </div>
                    </div>

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
                            <a href="" target="_blank"
                                class="text-primary text-hover-primary">{{ env('APP_NAME') }}</a>
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
                <form id="logout-form" action="{{ route('agent.logout') }}" method="POST" style="display: none;">
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
                <a href="{{ route('agent.logout') }}" onclick="event.preventDefault();
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
            <form action="{{ route('agent.change-password') }}" method="post" id="formChangePassword">
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

    <input type="hidden" id="notificationCount" value="0">

    @include('parts.agent.javascript')

    @yield('javascript')

    {!! JsValidator::formRequest('App\Http\Requests\PasswordRequest', '#formChangePassword') !!}

    <script>
        const soundAlert = new Audio("sound/sound-alert-2.wav");
        // request permission on page load
        // document.addEventListener('DOMContentLoaded', function() {
        //     if (!Notification) {
        //         alert('Desktop notifications not available in your browser. Try Chromium.');
        //         return;
        //     }

        //     if (Notification.permission !== 'granted')
        //         Notification.requestPermission();
        // });

        // function notifyMe() {
        //     if (Notification.permission !== 'granted')
        //         Notification.requestPermission();
        //     else {
        //         var notification = new Notification('Notification title', {
        //             // icon: '{{ asset('images/fastX.png') }}',
        //             body: 'Hey there! You\'ve been notified!',
        //         });
        //         notification.onclick = function() {
        //             // window.open('http://stackoverflow.com/a/13328397/1269037');
        //         };
        //     }
        // }
        var notificationCount = $('#notificationCount').val();

        $(function() {
            setInterval(() => {
                checkNotification();
            }, 10000);
        });

        function checkNotification() {

            $.get('{{ route('agent.notification') }}', function(r) {
                if (r.length > 0) {
                    soundAlert.play();
                }
            });

        }
    </script>

</body>
<!--end::Body-->

</html>
