`
<!--begin::Nav Wrapper-->
<div class="aside-nav d-flex flex-column align-items-center flex-column-auto pb-10">
    <!--begin::Nav-->
    <ul class="nav flex-column">
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="ภาพรวม">
            <a href="{{ route('agent') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg active">
                <i class="fas fa-layer-group d-block"></i>
            </a>
            <span class="d-block text-white">ภาพรวม</span>
        </li>
        {{-- <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="บอท">
            <a href="{{route('agent.bot')}}" target="_blank" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fas fa-desktop"></i>
            </a>
            <span class="d-block text-white">มอนิเตอร์</span>
        </li> --}}
        @if (Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4)
            <!--end::Item-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="จัดการผู้ใช้งาน">
                <a href="{{ route('agent.user') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <i class="fa fa-users"></i>
                </a>
                <span class="d-block text-white">จัดการผู้ใช้งาน</span>
            </li>
            <!--begin::Item-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="โปรโมชั่น">
                <a href="{{ route('agent.promotion') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <i class="fa fa-tags"></i>
                </a>
                <span class="d-block text-white">โปรโมชั่น</span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="โค้ดเครดิตฟรี">
                <a href="{{ route('agent.credit-free') }}"
                    class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <i class="fa fa-qrcode"></i>
                </a>
                <span class="d-block text-white">โค้ดเครดิตฟรี</span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="วงล้อ">
                <a href="{{ route('agent.wheel') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <i class="fa fa-dharmachakra"></i>
                </a>
                <span class="d-block text-white">วงล้อ</span>
            </li>
            <!--end::Item-->
        @endif
        <!--begin::Item-->
        <!--end::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="ลูกค้า">
            <a href="{{ route('agent.customer') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fa fa-users"></i>
            </a>
            <span class="d-block text-white">ลูกค้า</span>
        </li>
        <!--begin::Item-->
        <!--end::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="การตลาด">
            <a href="{{ route('agent.marketing.top') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fas fa-balance-scale"></i>
            </a>
            <span class="d-block text-white">การตลาด</span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="ถอนเงิน">
            <a href="{{ route('agent.withdraw') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fa fa-credit-card"></i>
            </a>
            <span class="d-block text-white">ถอนเงิน</span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="เติมมือ">
            <a href="{{ route('agent.manual') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                <i class="fa fa-hand-holding-usd"></i>
            </a>
            <span class="d-block text-white">เติมมือ</span>
        </li>
        <!--end::Item-->
        @if (Auth::user()->user_role_id == 2 || Auth::user()->user_role_id == 4)
            <!--end::Item-->
            <li class="nav-item mb-2" id="kt_quick_finance_toggle" data-toggle="tooltip" data-placement="right"
                data-container="body" data-boundary="window" title="การเงิน">
                <a href="{{ route('agent.withdraw') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <i class="fa fa-dollar-sign"></i>
                </a>
                <span class="d-block text-white">การเงิน</span>
            </li>
            <!--begin::Item-->
            <div id="kt_quick_finance" class="offcanvas offcanvas-left p-10">
                <!--begin::Header-->
                <div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
                    <h3 class="font-weight-bold m-0"> <i class="fa fa-dollar-sign"></i> การเงิน</h3>
                </div>
                <!--end::Header-->
                <!--begin::Content-->
                <div class="offcanvas-content pr-5 mr-n5">
                    <!--begin::Separator-->
                    <div class="separator separator-dashed mt-8 mb-5"></div>
                    <!--end::Separator-->
                    <!--begin::Nav-->
                    <div class="navi navi-spacer-x-0 p-0">
                        <a href="{{ route('agent.transfer') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> โยกเงิน</div>
                                    <div class="text-muted">Move</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('agent.withdraw-finance') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> เบิกจ่าย</div>
                                    <div class="text-muted">Withdraw</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('agent.receive') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> รายรับ</div>
                                    <div class="text-muted">Receive</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('agent.return') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> โอนเงิน</div>
                                    <div class="text-muted">Transfer</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!--end::Nav-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Item-->
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
                        <a href="{{ route('agent.report.summary') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> สรุปรายได้</div>
                                    <div class="text-muted">Summary</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('agent.report.customer') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> ลูกค้า</div>
                                    <div class="text-muted">Customer</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('agent.report.deposit') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> เติมเงิน</div>
                                    <div class="text-muted">Deposit</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('agent.report.withdraw') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> ถอนเงิน</div>
                                    <div class="text-muted">Withdraw</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('agent.report.promotion') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> รายจ่ายโปรโมชั่น</div>
                                    <div class="text-muted">Promotion</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('agent.report.statement') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> ประวัติการเดินบัญชี</div>
                                    <div class="text-muted">Statement</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('agent.report.event') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> ประวัติการทำงานของพนักงาน</div>
                                    <div class="text-muted">History</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('agent.report.bank-account-transaction') }}" class="navi-item">
                            <div class="navi-link">
                                <div class="navi-text">
                                    <div class="font-weight-bold"> การทำงานของบอท</div>
                                    <div class="text-muted">Bot</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!--end::Nav-->
                </div>
                <!--end::Content-->
            </div>
            <!--begin::Item-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="เลขที่บัญชีธนาคาร">
                <a href="{{ route('agent.bank-account') }}"
                    class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <i class="fa fa-university"></i>
                </a>
                <span class="d-block text-white">เลขที่บัญชีธนาคาร</span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body"
                data-boundary="window" title="ตั้งค่า">
                <a href="{{ route('agent.brand') }}" class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <i class="fa fa-cog"></i>
                </a>
                <span class="d-block text-white">ตั้งค่า</span>
            </li>
            <!--end::Item-->
        @endif
    </ul>
    <!--end::Nav-->
</div>
<!--end::Nav Wrapper-->

<div class="aside-footer d-flex flex-column align-items-center flex-column-auto py-8">
    <!--begin::Languages-->
    <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"
        class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative" id="kt_quick_notifications_toggle"
        data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="ออกจากระบบ">
        <i class="fa fa-sign-out-alt"></i>
    </a>
    <!--end::Languages-->
</div>
