@extends('layouts.admin')

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
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{ env('APP_NAME') }}</h5>
                    <!--end::Page Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin') }}" class="text-muted">ภาพรวม</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-dark">

                                จัดการเกมส์</a>
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
                    <i class="fa fa-plus"></i>เพิ่มเกมส์</a>
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
            <!--begin::Card-->
            <div class="row">
                <input type="hidden" id="games" value="{{ $games->pluck('id') }}">
                @foreach ($games as $game)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <!--begin::Card-->
                        <div class="card card-custom gutter-b card-stretch">
                            <!--begin::Body-->
                            <div class="card-body text-center pt-4">
                                <!--begin::Toolbar-->
                                <div class="d-flex justify-content-end">
                                    <div class="dropdown dropdown-inline" data-toggle="tooltip" title=""
                                        data-placement="top" data-original-title="แก้ไข">
                                        <a href="#" class="btn btn-clean btn-hover-light-warning btn-sm btn-icon"
                                            data-toggle="modal" data-target="#editGameModal_{{ $game->id }}">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                    </div>
                                    <div class="dropdown dropdown-inline" data-toggle="tooltip" title=""
                                        data-placement="top" data-original-title="ลบ">
                                        <a href="#" class="btn btn-clean btn-hover-light-danger btn-sm btn-icon"
                                            data-toggle="modal" data-target="#deleteGameModal_{{ $game->id }}">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                <!--end::Toolbar-->

                                <!--begin::User-->
                                <div class="mt-7">
                                    <div class="symbol symbol-circle symbol-lg-75">
                                        <img src="{{ $game->logo_url }}" alt="" class="img-fluid"
                                            style="width: 150px" />
                                    </div>
                                    <div class="symbol symbol-lg-75 symbol-circle symbol-primary d-none">
                                        <span class="font-size-h3 font-weight-boldest">{{ $game->name }}</span>
                                    </div>
                                </div>
                                <!--end::User-->

                                <!--begin::Name-->
                                <div class="my-2">
                                    <a href="#"
                                        class="text-dark font-weight-bold text-hover-primary font-size-h4">{{ $game->name }}</a>
                                </div>
                                <!--end::Name-->


                                <!--begin::Buttons-->
                                <div class="mt-9 mb-6">
                                    <a href="#" class="btn btn-md btn-icon btn-light-facebook btn-pill mx-2"
                                        data-toggle="tooltip" title="" data-placement="top"
                                        data-original-title="ทางเข้าเล่นผ่านเว็บ">
                                        <i class="fab fa-internet-explorer"></i>
                                    </a>
                                    <a href="#" class="btn btn-md btn-icon btn-light-twitter btn-pill mx-2"
                                        data-toggle="tooltip" title="" data-placement="top"
                                        data-original-title="ทางเข้าเล่น android">
                                        <i class="socicon-android"></i>
                                    </a>
                                    <a href="#" class="btn btn-md btn-icon btn-light-linkedin btn-pill  mx-2"
                                        data-toggle="tooltip" title="" data-placement="top" data-original-title="แก้ไข">
                                        <i class="socicon-apple"></i>
                                    </a>
                                </div>
                                <!--end::Buttons-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!-- Modal-->
                    <div class="modal fade" id="editGameModal_{{ $game->id }}" data-backdrop="static" tabindex="-1"
                        role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <form action="{{ route('admin.game.update') }}" method="post" id="formUpdateGame"
                                enctype="multipart/form-data">
                                <input type="hidden" name="game_id" value="{{ $game->id }}" />
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มเกมส์</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"
                                            aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label text-right">โลโก้เกมส์</label>
                                            <div class="col-lg-9 col-xl-6 pull-right">
                                                <div class="image-input image-input-outline"
                                                    id="kt_image_{{ $game->id }}">
                                                    <div class="image-input-wrapper"
                                                        style="background-image: url({{ $game->logo_url }})"></div>

                                                    <label
                                                        class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                        data-action="change" data-toggle="tooltip" title=""
                                                        data-original-title="Upload Logo">
                                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                                        <input type="file" name="logo" accept=".png, .jpg, .jpeg" />
                                                        <input type="hidden" name="logo_remove" />
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
                                            <label class="col-xl-3 col-lg-3 col-form-label text-right">ชื่อเกมส์</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ $game->name }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label text-right">ทางเข้าเล่น
                                                (เว็บ)</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <input type="text" class="form-control" name="url_web"
                                                    value="{{ $game->url_web }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label text-right">ทางเข้าเล่น
                                                (Android)</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <input type="text" class="form-control" name="url_android"
                                                    value="{{ $game->url_android }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label text-right">ทางเข้าเล่น
                                                (IOS)</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <input type="text" class="form-control" name="url_ios"
                                                    value="{{ $game->url_ios }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label text-right">แนะนำเกมส์</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <textarea name="description" id="" cols="30" rows="10" class="form-control">
                                                {{ $game->description }}
                                            </textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-warning font-weight-bold"
                                            data-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn btn-warning font-weight-bold">บันทึก</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Modal-->
                    <div class="modal fade" id="deleteGameModal_{{ $game->id }}" data-backdrop="static"
                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('admin.game.delete') }}" method="post">
                                <input type="hidden" name="game_id" value="{{ $game->id }}">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">ยืนยันการลบ ?</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-danger font-weight-bold"
                                            data-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn btn-danger font-weight-bold">ยืนยัน</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row pull-right">
                <div class="">
                    {{ $games->links() }}
                </div>
            </div>
            <!--end::Card-->
            <!-- Button trigger modal-->
            <!-- Modal-->
            <div class="modal fade" id="createGameMomdal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <form action="{{ route('admin.game.store') }}" method="post" id="formCreateGame"
                        enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มเกมส์</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">โลโก้เกมส์</label>
                                    <div class="col-lg-9 col-xl-6 pull-right">
                                        <div class="image-input image-input-outline" id="kt_image">
                                            <div class="image-input-wrapper" style=""></div>

                                            <label
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="change" data-toggle="tooltip" title=""
                                                data-original-title="Upload Logo">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="logo" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="logo_remove" />
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
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">ชื่อเกมส์</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">ทางเข้าเล่น (เว็บ)</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="url_web">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">ทางเข้าเล่น
                                        (Android)</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="url_android">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">ทางเข้าเล่น (IOS)</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="url_ios">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label text-right">แนะนำเกมส์</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <textarea name="description" id="" cols="30" rows="10" class="form-control">

                                    </textarea>
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
    {!! JsValidator::formRequest('App\Http\Requests\GameRequest', '#formCreateGame') !!}

    <script>
        // Example 4
        var avatar_4 = new KTImageInput('kt_image');

        var games = $('#games').val().replace('[', '').replace(']', '').split(',');

        $.each(games, function(k, v) {
            new KTImageInput('kt_image_' + v);
        });

        $(function() {

        });
    </script>
@endsection
