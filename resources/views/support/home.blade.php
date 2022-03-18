@extends('layouts.support')

@section('css')

@endsection

@section('content')

    <div class="content d-flex flex-column flex-column-fluid">
        <div class="d-flex flex-column-fluid">
            {{-- @if (Auth::user()->user_role_id != 3) --}}
            <div class="container-fluid">
                <h3 class="text-dark ">

                    {{-- <div class="clearfix"></div> --}}<i class="fas fa-tachometer-alt"></i> แผงควบคุมการทำงาน
                </h3>
                <div class="clearfix"></div>
                <hr>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="pull-right">
                            <p> รีเฟรชใน
                                <span id="secondTransaction">5</span> วินาที
                            </p>
                        </div>
                        <h3 class=""> <i class=" fa fa-money-check-alt mr-3"></i>
                            รายการ transaction ล่าสุด</h3>
                        <hr>
                        <div class="card card-custom card-shadowless gutter-b bg-white">

                            <div id="tableTransaction">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>วันที่/เวลา</th>
                                            <th>แบรนด์</th>
                                            <th>statementกำกับ</th>
                                            <th>สถานะ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bank_account_transactions as $bank_account_transaction)
                                            <tr>
                                                <td align="center">
                                                    {{ $bank_account_transaction->created_at->format('d/m/Y') }}
                                                    <br>
                                                    {{ $bank_account_transaction->created_at->format('H:i:s') }}
                                                </td>
                                                <td>{{ $bank_account_transaction->brand->name }}</td>
                                                <td>{{ $bank_account_transaction->bank_account }} /
                                                    {{ $bank_account_transaction->amount }}</td>
                                                <td>
                                                    @if ($bank_account_transaction->status == 0)
                                                        <span class="text-info">
                                                            <i class="fas fa-robot mr-2"></i>
                                                            รอบอทเติมเงิน
                                                        </span>
                                                    @elseif($bank_account_transaction->status == 1)
                                                        <span class="text-success">
                                                            <i class="fa fa-check mr-2"></i>
                                                            เติมเงินเสร็จแล้ว
                                                        </span>
                                                        <span class="text-center">
                                                            @if ($bank_account_transaction->deposit)
                                                                <p>
                                                                    ลูกค้า:
                                                                    {{ $bank_account_transaction->deposit->customer->username }}
                                                                </p>
                                                            @endif
                                                        </span>
                                                    @elseif($bank_account_transaction->status == 2)
                                                        <span class="text-warning mr-2">
                                                            <i class="far fa-clock"></i>
                                                            กำลังเชื่อมต่อ API
                                                        </span>
                                                    @elseif($bank_account_transaction->status == 3)
                                                        <span class="text-danger mr-2">
                                                            <i class="fa fa-times"></i>
                                                            เบิ้ล
                                                        </span>
                                                    @elseif($bank_account_transaction->status == 4)
                                                        <span class="text-danger mr-2">
                                                            <i class="fa fa-times"></i>
                                                            ไม่พบบัญชีนี้ในระบบ
                                                        </span>
                                                    @elseif($bank_account_transaction->status == 5)
                                                        <span class="text-warning mr-2">
                                                            <i class="fa fa-times"></i>
                                                            รายการนี้เติมมือแล้ว
                                                        </span>
                                                    @elseif($bank_account_transaction->status == 6)
                                                        <span class="text-warning mr-2">
                                                            <i class="fa fa-times"></i>
                                                            ติดโปรโมชั่น
                                                        </span>
                                                    @elseif($bank_account_transaction->status == 8)
                                                        <span class="text-warning mr-2">
                                                            <i class="fa fa-times"></i>
                                                            เลขที่บัญชี SCB 4 หลักซ้ำกัน
                                                        </span>
                                                    @elseif($bank_account_transaction->status == 9)
                                                        <span class="text-danger mr-2">
                                                            <i class="fa fa-times"></i>
                                                            ลูกค้าออนไลน์อยู่
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8" id="tableHelper">
                        <div class="pull-right">
                            <p> รีเฟรชใน
                                <span id="secondHelper">30</span> วินาที
                            </p>
                        </div>
                        <h3> <i class="fa fa-question-circle"></i> รายการการแจ้งปัญหาล่าสุด</h3>
                        <hr>
                        <div class="card card-custom card-shadowless gutter-b bg-white">
                            <!--begin::Header-->
                            <div class="card-header border-0">
                                <h3 class="card-title font-weight-bolder text-dark">Todo</h3>
                                <div class="card-toolbar">
                                    <div class="dropdown dropdown-inline">
                                        <a href="#"
                                            class="btn btn-light btn-sm font-size-sm font-weight-bolder dropdown-toggle text-dark-75"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Create
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover">
                                                <li class="navi-header pb-1">
                                                    <span
                                                        class="text-primary text-uppercase font-weight-bold font-size-sm">Add
                                                        new:</span>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i
                                                                class="flaticon2-shopping-cart-1"></i></span>
                                                        <span class="navi-text">Order</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i
                                                                class="flaticon2-calendar-8"></i></span>
                                                        <span class="navi-text">Event</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i
                                                                class="flaticon2-graph-1"></i></span>
                                                        <span class="navi-text">Report</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i
                                                                class="flaticon2-rocket-1"></i></span>
                                                        <span class="navi-text">Post</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i
                                                                class="flaticon2-writing"></i></span>
                                                        <span class="navi-text">File</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-2">
                                <!--begin::Item-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Bullet-->
                                    <span class="bullet bullet-bar bg-success align-self-stretch"></span>
                                    <!--end::Bullet-->

                                    <!--begin::Checkbox-->
                                    <label
                                        class="checkbox checkbox-lg checkbox-light-success checkbox-inline flex-shrink-0 m-0 mx-4">
                                        <input type="checkbox" name="select" value="1">
                                        <span></span>
                                    </label>
                                    <!--end::Checkbox-->

                                    <!--begin::Text-->
                                    <div class="d-flex flex-column flex-grow-1">
                                        <a href="#"
                                            class="text-dark-75 text-hover-primary font-weight-bold font-size-lg mb-1">
                                            Create FireStone Logo
                                        </a>
                                        <span class="text-muted font-weight-bold">
                                            Due in 2 Days
                                        </span>
                                    </div>
                                    <!--end::Text-->

                                    <!--begin::Dropdown-->
                                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title=""
                                        data-placement="left" data-original-title="Quick actions">
                                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ki ki-bold-more-hor"></i>
                                        </a>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover">
                                                <li class="navi-header font-weight-bold py-4">
                                                    <span class="font-size-lg">Choose Label:</span>
                                                    <i class="flaticon2-information icon-md text-muted"
                                                        data-toggle="tooltip" data-placement="right" title=""
                                                        data-original-title="Click to learn more..."></i>
                                                </li>
                                                <li class="navi-separator mb-3 opacity-70"></li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-success">Customer</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-danger">Partner</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-warning">Suplier</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-primary">Member</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-dark">Staff</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-separator mt-3 opacity-70"></li>
                                                <li class="navi-footer py-4">
                                                    <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                                        <i class="ki ki-plus icon-sm"></i>
                                                        Add new
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                    <!--end::Dropdown-->
                                </div>
                                <!--end:Item-->

                                <!--begin::Item-->
                                <div class="d-flex align-items-center mt-10">
                                    <!--begin::Bullet-->
                                    <span class="bullet bullet-bar bg-primary align-self-stretch"></span>
                                    <!--end::Bullet-->

                                    <!--begin::Checkbox-->
                                    <label
                                        class="checkbox checkbox-lg checkbox-light-primary checkbox-inline flex-shrink-0 m-0 mx-4">
                                        <input type="checkbox" value="1">
                                        <span></span>
                                    </label>
                                    <!--end::Checkbox-->

                                    <!--begin::Text-->
                                    <div class="d-flex flex-column flex-grow-1">
                                        <a href="#"
                                            class="text-dark-75 text-hover-primary font-weight-bold font-size-lg mb-1">
                                            Stakeholder Meeting
                                        </a>
                                        <span class="text-muted font-weight-bold">
                                            Due in 3 Days
                                        </span>
                                    </div>
                                    <!--end::Text-->

                                    <!--begin::Dropdown-->
                                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title=""
                                        data-placement="left" data-original-title="Quick actions">
                                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ki ki-bold-more-hor"></i>
                                        </a>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover">
                                                <li class="navi-header font-weight-bold py-4">
                                                    <span class="font-size-lg">Choose Label:</span>
                                                    <i class="flaticon2-information icon-md text-muted"
                                                        data-toggle="tooltip" data-placement="right" title=""
                                                        data-original-title="Click to learn more..."></i>
                                                </li>
                                                <li class="navi-separator mb-3 opacity-70"></li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-success">Customer</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-danger">Partner</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-warning">Suplier</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-primary">Member</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-dark">Staff</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-separator mt-3 opacity-70"></li>
                                                <li class="navi-footer py-4">
                                                    <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                                        <i class="ki ki-plus icon-sm"></i>
                                                        Add new
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                    <!--end::Dropdown-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="d-flex align-items-center mt-10">
                                    <!--begin::Bullet-->
                                    <span class="bullet bullet-bar bg-warning align-self-stretch"></span>
                                    <!--end::Bullet-->

                                    <!--begin::Checkbox-->
                                    <label
                                        class="checkbox checkbox-lg checkbox-light-warning checkbox-inline flex-shrink-0 m-0 mx-4">
                                        <input type="checkbox" value="1">
                                        <span></span>
                                    </label>
                                    <!--end::Checkbox-->

                                    <!--begin::Text-->
                                    <div class="d-flex flex-column flex-grow-1">
                                        <a href="#"
                                            class="text-dark-75 text-hover-primary font-size-sm font-weight-bold font-size-lg mb-1">
                                            Scoping &amp; Estimations
                                        </a>
                                        <span class="text-muted font-weight-bold">
                                            Due in 5 Days
                                        </span>
                                    </div>
                                    <!--end::Text-->

                                    <!--begin: Dropdown-->
                                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title=""
                                        data-placement="left" data-original-title="Quick actions">
                                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ki ki-bold-more-hor"></i>
                                        </a>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover">
                                                <li class="navi-header font-weight-bold py-4">
                                                    <span class="font-size-lg">Choose Label:</span>
                                                    <i class="flaticon2-information icon-md text-muted"
                                                        data-toggle="tooltip" data-placement="right" title=""
                                                        data-original-title="Click to learn more..."></i>
                                                </li>
                                                <li class="navi-separator mb-3 opacity-70"></li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-success">Customer</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-danger">Partner</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-warning">Suplier</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-primary">Member</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-dark">Staff</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-separator mt-3 opacity-70"></li>
                                                <li class="navi-footer py-4">
                                                    <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                                        <i class="ki ki-plus icon-sm"></i>
                                                        Add new
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                    <!--end::Dropdown-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="d-flex align-items-center mt-10">
                                    <!--begin::Bullet-->
                                    <span class="bullet bullet-bar bg-info align-self-stretch"></span>
                                    <!--end::Bullet-->

                                    <!--begin::Checkbox-->
                                    <label
                                        class="checkbox checkbox-lg checkbox-light-info checkbox-inline flex-shrink-0 m-0 mx-4">
                                        <input type="checkbox" value="1">
                                        <span></span>
                                    </label>
                                    <!--end::Checkbox-->

                                    <!--begin::Text-->
                                    <div class="d-flex flex-column flex-grow-1">
                                        <a href="#"
                                            class="text-dark-75 text-hover-primary font-weight-bold font-size-lg mb-1">
                                            Sprint Showcase
                                        </a>
                                        <span class="text-muted font-weight-bold">
                                            Due in 1 Day
                                        </span>
                                    </div>
                                    <!--end::Text-->

                                    <!--begin::Dropdown-->
                                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title=""
                                        data-placement="left" data-original-title="Quick actions">
                                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ki ki-bold-more-hor"></i>
                                        </a>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover py-5">
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i class="flaticon2-drop"></i></span>
                                                        <span class="navi-text">New Group</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i
                                                                class="flaticon2-list-3"></i></span>
                                                        <span class="navi-text">Contacts</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i
                                                                class="flaticon2-rocket-1"></i></span>
                                                        <span class="navi-text">Groups</span>
                                                        <span class="navi-link-badge">
                                                            <span
                                                                class="label label-light-primary label-inline font-weight-bold">new</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i
                                                                class="flaticon2-bell-2"></i></span>
                                                        <span class="navi-text">Calls</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i class="flaticon2-gear"></i></span>
                                                        <span class="navi-text">Settings</span>
                                                    </a>
                                                </li>

                                                <li class="navi-separator my-3"></li>

                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i
                                                                class="flaticon2-magnifier-tool"></i></span>
                                                        <span class="navi-text">Help</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i
                                                                class="flaticon2-bell-2"></i></span>
                                                        <span class="navi-text">Privacy</span>
                                                        <span class="navi-link-badge">
                                                            <span
                                                                class="label label-light-danger label-rounded font-weight-bold">5</span>
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                    <!--end::Dropdown-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="d-flex align-items-center mt-10">
                                    <!--begin::Bullet-->
                                    <span class="bullet bullet-bar bg-danger align-self-stretch"></span>
                                    <!--end::Bullet-->

                                    <!--begin::Checkbox-->
                                    <label
                                        class="checkbox checkbox-lg checkbox-light-danger checkbox-inline flex-shrink-0 m-0 mx-4">
                                        <input type="checkbox" value="1">
                                        <span></span>
                                    </label>
                                    <!--end::Checkbox:-->

                                    <!--begin::Title-->
                                    <div class="d-flex flex-column flex-grow-1">
                                        <a href="#"
                                            class="text-dark-75 text-hover-primary font-weight-bold font-size-lg mb-1">
                                            Project Retro
                                        </a>
                                        <span class="text-muted font-weight-bold">
                                            Due in 12 Days
                                        </span>
                                    </div>
                                    <!--end::Text-->

                                    <!--begin: Dropdown-->
                                    <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title=""
                                        data-placement="left" data-original-title="Quick actions">
                                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ki ki-bold-more-hor"></i>
                                        </a>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover">
                                                <li class="navi-header font-weight-bold py-4">
                                                    <span class="font-size-lg">Choose Label:</span>
                                                    <i class="flaticon2-information icon-md text-muted"
                                                        data-toggle="tooltip" data-placement="right" title=""
                                                        data-original-title="Click to learn more..."></i>
                                                </li>
                                                <li class="navi-separator mb-3 opacity-70"></li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-success">Customer</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-danger">Partner</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-warning">Suplier</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-primary">Member</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-text">
                                                            <span
                                                                class="label label-xl label-inline label-light-dark">Staff</span>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="navi-separator mt-3 opacity-70"></li>
                                                <li class="navi-footer py-4">
                                                    <a class="btn btn-clean font-weight-bold btn-sm" href="#">
                                                        <i class="ki ki-plus icon-sm"></i>
                                                        Add new
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                    <!--end::Dropdown-->
                                </div>
                                <!--end::Item-->
                            </div>
                            <!--end::Body-->
                        </div>
                    </div>
                </div>
            </div>
            {{-- @endif --}}
        </div>
    </div>

@endsection

@section('javascript')

    <script>
        // Class definition
        $(function() {
            // enable clear button 
            $('#kt_datepicker_2, #kt_datepicker_3_validate').datepicker({
                todayBtn: "linked",
                todayHighlight: true,
                format: 'dd/mm/yyyy',
            });
            // enable clear button 
            $('#kt_datepicker_1, #kt_datepicker_3_validate').datepicker({
                todayBtn: "linked",
                todayHighlight: true,
                format: 'dd/mm/yyyy',
            });

            var iSecondTransaction = 5;

            var iSecondHelper = 30;

            setInterval(function() {

                iSecondTransaction--;

                if (iSecondTransaction == 0) {

                    refershTransaction();

                    iSecondTransaction = 5;

                }

                $('#secondTransaction').html(iSecondTransaction);

            }, 1000);

            setInterval(function() {

                iSecondHelper--;

                if (iSecondHelper == 0) {

                    iSecondHelper = 30;

                }

                $('#secondHelper').html(iSecondHelper);

            }, 1000);

        });

        function refershTransaction() {

            $('#tableTransaction').load('/transaction');

        }
    </script>



@endsection
