@extends('layouts.support')

@section('css')
@endsection

@section('content')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-transparent" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Casnio auto</h5>
                    <!--end::Page Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('support') }}" class="text-muted">ภาพรวม</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-dark">

                                จัดการผู้ใช้งาน</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Actions-->
                <a href="#" class="btn btn-light-primary font-weight-bolder btn-sm" data-toggle="modal"
                    data-target="#createGameMomdal">
                    <i class="fa fa-plus"></i>เพิ่มผู้ใช้งาน</a>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-fluid-->
        <div class="container-fluid">
            <div class="row">
            </div>
            <!--begin::Card-->
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b card-stretch">
                        <!--begin::Body-->
                        <div class="card-body pt-4">
                            <form action="{{ route('support.user') }}" method="get">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <select name="brand_id" id="brand_id" class="form-control" required>
                                            <option value="">เลือกแบรนด์</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    @if ($brand_select == $brand->id) selected @endif>
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <select name="user_role_id" id="user_role_id" class="form-control">
                                            <option value="">ตำแหน่ง</option>
                                            @foreach ($user_roles as $user_role)
                                                <option value="{{ $user_role->id }}"
                                                    @if ($user_role_select == $user_role->id) selected @endif>
                                                    {{ $user_role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>สร้างเมื่อวันที่</th>
                                        <th>แบรนด์</th>
                                        <th>Username</th>
                                        <th>ตำแหน่ง</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->created_at }}</td>
                                            <td>
                                                @if (isset($user->brand))
                                                    {{ $user->brand->name }}
                                                @else
                                                    Team Support
                                                @endif
                                            </td>
                                            <td>{{ $user->username }} <br><small>{{ $user->name }}</small></td>
                                            <td>{{ $user->userRole->name }}</td>
                                            <td>
                                                <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i
                                                        class="fa fa-circle @if ($user->status == 1) text-success @else text-default @endif"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">

                                                    <!--begin::Navigation-->
                                                    <ul class="navi navi-hover">
                                                        <li class="navi-header font-weight-bold py-4">
                                                            <span class="font-size-lg">เปลี่ยนสถานะ</span>

                                                        </li>
                                                        <li class="navi-separator mb-3 opacity-70"></li>
                                                        <li class="navi-item">
                                                            <a href="#" class="navi-link"
                                                                onclick="updateStatus({{ $user->id }},1)">
                                                                <span class="navi-text">
                                                                    <span
                                                                        class="label label-xl label-inline label-light-success">เปิดใช้งาน</span>
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="navi-item"
                                                            onclick="updateStatus({{ $user->id }},0)">
                                                            <a href="#" class="navi-link">
                                                                <span class="navi-text">
                                                                    <span
                                                                        class="label label-xl label-inline label-light-danger">ปิดใช้งาน</span>
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="navi-item">
                                                            <a href=""><span class="navi-text">ข้อมูลการเข้าสู่ระบบ:
                                                                    {{ $user->ip }} {{ $user->browser }}
                                                                    {{ $user->operation }}</span></a>
                                                        </li>

                                                    </ul>
                                                    <!--end::Navigation-->
                                                </div>
                                                <br>
                                            </td>
                                            <td>

                                                <a href="#" data-toggle="modal"
                                                    data-target="#editUserModal_{{ $user->id }}"
                                                    class="btn btn-block btn-sm btn-light-warning font-weight-bolder text-uppercase py-4">แก้ไข</a>
                                                <a href="#"
                                                    class="btn btn-block btn-sm btn-danger font-weight-bolder text-uppercase py-4"
                                                    data-toggle="modal"
                                                    data-target="#deleteUserModal_{{ $user->id }}">ปิดการใช้งาน</a>

                                                <div class="modal fade" id="editUserModal_{{ $user->id }}"
                                                    data-backdrop="static" tabindex="-1" role="dialog"
                                                    aria-labelledby="staticBackdrop" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <form action="{{ route('support.user.update') }}" method="post"
                                                            id="formUpdateUser" enctype="multipart/form-data">
                                                            <input type="hidden" name="user_id"
                                                                value="{{ $user->id }}" />
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-warning">
                                                                    <h5 class="modal-title text-white"
                                                                        id="exampleModalLabel">แก้ไขผู้ใช้งาน</h5>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group row">
                                                                        <label
                                                                            class="col-xl-3 col-lg-3 col-form-label text-right">รูปผู้ใช้งาน</label>
                                                                        <div class="col-lg-9 col-xl-6 pull-right">
                                                                            <div class="image-input image-input-outline"
                                                                                id="kt_image_{{ $user->id }}">
                                                                                <div class="image-input-wrapper"
                                                                                    style="background-image: url({{ $user->img_url }})">
                                                                                </div>

                                                                                <label
                                                                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                                                    data-action="change"
                                                                                    data-toggle="tooltip" title=""
                                                                                    data-original-title="Upload Logo">
                                                                                    <i
                                                                                        class="fa fa-pen icon-sm text-muted"></i>
                                                                                    <input type="file" name="img"
                                                                                        accept=".png, .jpg, .jpeg" />
                                                                                    <input type="hidden"
                                                                                        name="img_remove" />
                                                                                </label>

                                                                                <span
                                                                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                                                    data-action="cancel"
                                                                                    data-toggle="tooltip"
                                                                                    title="Cancel Logo">
                                                                                    <i
                                                                                        class="ki ki-bold-close icon-xs text-muted"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label
                                                                            class="col-xl-3 col-lg-3 col-form-label text-right">แบรนด์</label>
                                                                        <div class="col-lg-4 col-xl-6">
                                                                            <select name="brand_id" id="brand_id"
                                                                                class="form-control" readonly disabled>
                                                                                <option value="">เลือก</option>
                                                                                @foreach ($brands as $brand)
                                                                                    <option value="{{ $brand->id }}"
                                                                                        @if ($user->brand_id == $brand->id) selected @endif>
                                                                                        {{ $brand->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label
                                                                            class="col-xl-3 col-lg-3 col-form-label text-right">ตำแหน่ง</label>
                                                                        <div class="col-lg-4 col-xl-6">
                                                                            <select name="user_role_id" id="user_role_id"
                                                                                class="form-control">
                                                                                <option value="">เลือก</option>
                                                                                @foreach ($user_roles as $user_role)
                                                                                    <option value="{{ $user_role->id }}"
                                                                                        @if ($user->user_role_id == $user_role->id) selected @endif>
                                                                                        {{ $user_role->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label
                                                                            class="col-xl-3 col-lg-3 col-form-label text-right">ชื่อเรียก</label>
                                                                        <div class="col-lg-9 col-xl-6">
                                                                            <input type="text" class="form-control"
                                                                                name="name" value="{{ $user->name }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-light-warning font-weight-bold"
                                                                        data-dismiss="modal">ยกเลิก</button>
                                                                    <button type="button"
                                                                        class="btn btn-warning font-weight-bold"
                                                                        onclick="resetPassword({{ $user->id }})">รีเซ็ตรหัสผ่าน</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary font-weight-bold">บันทึก</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="deleteUserModal_{{ $user->id }}"
                                                    data-backdrop="static" tabindex="-1" role="dialog"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form action="{{ route('support.user.delete') }}" method="post">
                                                            <input type="hidden" name="user_id"
                                                                value="{{ $user->id }}">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger">
                                                                    <h5 class="modal-title text-white"
                                                                        id="exampleModalLabel">ยืนยันการลบ ?</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-light-danger font-weight-bold"
                                                                        data-dismiss="modal">ยกเลิก</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger font-weight-bold">ยืนยัน</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="users" value="{{ $users->pluck('id') }}">
            </div>
            <div class="row pull-right">
                <div class="">
                    {{ $users->links() }}
                </div>
            </div>
            <!--end::Card-->
            <!-- Button trigger modal-->
            <!-- Modal-->
            <div class="
                    modal fade" id="createGameMomdal" data-backdrop="static" tabindex="-1"
                role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <form action="{{ route('support.user.store') }}" method="post" id="formCreateUser"
                        enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มผู้ใช้งาน</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">รูปผู้ใช้งาน</label>
                                    <div class="col-lg-9 col-xl-6 pull-right">
                                        <div class="image-input image-input-outline" id="kt_image">
                                            <div class="image-input-wrapper" style=""></div>

                                            <label
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="change" data-toggle="tooltip" title=""
                                                data-original-title="Upload Logo">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="img" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="img_remove" />
                                            </label>

                                            <span
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="cancel" data-toggle="tooltip" title="Cancel Logo">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">แบรนด์</label>
                                    <div class="col-lg-4 col-xl-6">
                                        <select name="brand_id" id="brand_id" class="form-control">
                                            <option value="">เลือก</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">ตำแหน่ง</label>
                                    <div class="col-lg-4 col-xl-6">
                                        <select name="user_role_id" id="user_role_id" class="form-control">
                                            <option value="">เลือก</option>
                                            @foreach ($user_roles as $user_role)
                                                <option value="{{ $user_role->id }}">{{ $user_role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">ชื่อเรียก</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Username</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="username" input-type="character">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">Password</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                    data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary font-weight-bold">เพิ่ม</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
@endsection

@section('javascript')
    {!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#formCreateUser') !!}

    <script>
        // Example 4
        var avatar_4 = new KTImageInput('kt_image');

        var users = $('#users').val().replace('[', '').replace(']', '').split(',');

        $.each(users, function(k, v) {
            new KTImageInput('kt_image_' + v);
        });

        function resetPassword(user_id) {

            if (confirm('รหัสผ่านจะถูกรีเซ็ตเป็น Aa123123')) {

                $.post('{{ route('support.user.reset-password') }}', {
                    user_id: user_id
                }, function() {
                    location.reload();
                });

            }

        }

        function updateStatus(user_id, status) {

            $.post('{{ route('support.user.update-status') }}', {
                user_id: user_id,
                status: status
            }, function(r) {
                location.reload();
            });

        }
    </script>
@endsection
