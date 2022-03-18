<!--begin::Nav Wrapper-->
<div class="aside-nav d-flex flex-column align-items-center flex-column-fluid pb-10">
    <!--begin::Nav-->
    <ul class="nav flex-column">
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="ภาพรวม">
            <a href="{{ route('super') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg active">
                <i class="fas fa-layer-group d-block"></i>
            </a>
            <span class="d-block text-white">ภาพรวม</span>
        </li>
        <li class="nav-item mb-2" id="kt_quick_report_toggle" data-toggle="tooltip" data-placement="right"
            data-container="body" data-boundary="window" title="รายงาน">
            <a href="{{ route('agent.withdraw') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fa fa-chart-pie"></i>
            </a>
            <span class="d-block text-white">รายงาน</span>
        </li>
        <div id="kt_quick_report" class="offcanvas offcanvas-left p-10">
            <!--begin::Header-->
            <div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
                <h3 class="font-weight-bold m-0"> <i class="fa fa-chart-pie pr-5"></i>รายงาน</h3>
            </div>
            <!--end::Header-->
            <!--begin::Content-->
            <div class="offcanvas-content pr-5 mr-n5">
                <!--begin::Separator-->
                <div class="separator separator-dashed mt-8 mb-5"></div>
                <!--end::Separator-->
                <!--begin::Nav-->
                <div class="navi navi-spacer-x-0 p-0">
                    <a href="{{ route('super.report.deposit') }}" class="navi-item">
                        <div class="navi-link">
                            <div class="navi-text">
                                <div class="font-weight-bold"> รายงานการเติม/ถอน</div>
                                <div class="text-muted">Deposit/Withdraw</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('super.report.customer') }}" class="navi-item">
                        <div class="navi-link">
                            <div class="navi-text">
                                <div class="font-weight-bold"> ลูกค้า</div>
                                <div class="text-muted">Customer</div>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end::Nav-->
            </div>
            <!--end::Content-->
        </div>
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
    <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"
        class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative" id="kt_quick_notifications_toggle"
        data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="ออกจากระบบ">
        <i class="fa fa-sign-out-alt"></i>
    </a>
    <!--end::Languages-->
</div>
