<!--begin::Nav Wrapper-->
<div class="aside-nav d-flex flex-column align-items-center flex-column-fluid pb-10">
    <!--begin::Nav-->
    <ul class="nav flex-column">
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="ภาพรวม">
            <a href="{{ route('support') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg active">
                <i class="fas fa-layer-group d-block"></i>
            </a>
            <span class="d-block text-white">ภาพรวม</span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="">
            <a href="{{ route('support.annoucement') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fa fa-bullhorn"></i>
            </a>
            <span class="d-block text-white">ข้อความประกาศ</span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="">
            <a href="{{ route('support.game') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fa fa-gamepad"></i>
            </a>
            <span class="d-block text-white">จัดการเกมส์</span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="">
            <a href="{{ route('support.brand') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fa la-flag"></i>
            </a>
            <span class="d-block text-white">จัดการแบรนด์</span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="">
            <a href="{{ route('support.bank-account') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fa fa-university"></i>
            </a>
            <span class="d-block text-white">เลขที่บัญชีธนาคาร</span>
        </li>
        <!--end::Item-->
        @if (Auth::user()->user_role_id == 7)
            <!--begin::Item-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="จัดการแบรนด์">
                <a href="{{ route('support.truemoney') }}"
                    class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <i class="fab fa-tumblr"></i>
                </a>
                <span class="d-block text-white">บัญชีทรูมันนี่</span>
            </li>
            <!--end::Item-->
        @endif
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="">
            <a href="{{ route('support.user') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fa fa-users"></i>
            </a>
            <span class="d-block text-white">จัดการผู้ใช้งาน</span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="">
            <a href="{{ route('support.logs') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="far fa-robot"></i>
            </a>
            <span class="d-block text-white">LOGS</span>
        </li>
        <!--end::Item-->
    </ul>
    <!--end::Nav-->
</div>
<!--end::Nav Wrapper-->

<div class="aside-footer d-flex flex-column align-items-center flex-column-auto py-8">
    <!--begin::Notifications-->
    {{-- <a class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative" id="kt_quick_notifications_toggle"
        data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="แจ้งแตือน">
        <i class="fas fa-bell"></i>
        <span
            class="label label-sm label-light-danger label-rounded font-weight-bolder position-absolute top-0 right-0 mt-1 mr-1">3</span>
    </a> --}}
    <!--end::Quick Panel-->
    <!--begin::Languages-->
    <a href="{{ route('support.logout') }}" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"
        class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative" id="kt_quick_notifications_toggle"
        data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="ออกจากระบบ">
        <i class="fa fa-sign-out-alt"></i>
    </a>
    <!--end::Languages-->
</div>
