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
                            <a href="{{ route('agent') }}" class="text-muted">ภาพรวม</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-dark">
                                จัดการริชเมนู</a>
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
                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                </div>
                <!--end::Toolbar-->
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
            <div class="card card-custom card-shadowless">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">
                            <i class="fab fa-elementor mr-2"></i>
                            ริชเมนู
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="pull-right">
                        <button class="btn btn-primary" data-target="#createRichMenuImage" data-toggle="modal">
                            เพิ่มริชเมนู
                        </button>
                    </div>
                    <h3>เมนูของ {{ $brand->name }}</h3>
                    <small>{{ $brand->line_token }}</small>
                    <div class="clearfix"></div>
                    <hr>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>chatBarText</td>
                                <td>RichMenuId</td>
                                <td>Size</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rich_menus['richmenus'] as $richmenu)
                                {{-- @php
                                
                                $curl = curl_init();
                                
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => "https://api-data.line.me/v2/bot/richmenu/".$richmenu['richMenuId']."/content",
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => "",
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => "GET",
                                    CURLOPT_HTTPHEADER => array(
                                        "Authorization: Bearer $brand->line_token"
                                    ),  
                                ));

                                $response = curl_exec($curl);

                                curl_close($curl);

                                $response = json_decode($response, true);

                                print_R($response);

                            @endphp --}}
                                <tr>
                                    <td>{{ $richmenu['name'] }}</td>
                                    <td>{{ $richmenu['chatBarText'] }}</td>
                                    <td>{{ $richmenu['richMenuId'] }}</td>
                                    <td>{{ $richmenu['size']['width'] }} x {{ $richmenu['size']['height'] }}</td>
                                    <td width="200">
                                        {{-- <a href="{{route('admin.rich-menu.default', ['brand_id' => $brand->id, 'rich_menu_id' => $richmenu['richMenuId']])}}" class="btn btn-primary">
                                        Set Default
                                    </a> --}}
                                        <button class="btn btn-primary"
                                            data-target="#modalUploadImage_{{ $richmenu['richMenuId'] }}"
                                            data-toggle="modal">
                                            Upload
                                        </button>
                                        <a href="{{ route('admin.rich-menu.delete', ['brand_id' => $brand->id, 'rich_menu_id' => $richmenu['richMenuId']]) }}"
                                            class="btn btn-danger">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                                <!-- Modal-->
                                <div class="modal fade" id="modalUploadImage_{{ $richmenu['richMenuId'] }}"
                                    data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <form action="{{ route('admin.rich-menu.upload') }}" id="formCreateRichMenu"
                                            method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="brand_id" value="{{ $brand->id }}">
                                            <input type="hidden" name="rich_menu_id" value="{{ $richmenu['richMenuId'] }}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">อัพโหลดริชเมนู</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="">RICH MENU IMAGE</label>
                                                            <input type="file" name="rich_menu_image"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold"
                                                        data-dismiss="modal">ยกเลิก</button>
                                                    <button type="submit"
                                                        class="btn btn-primary font-weight-bold">เพิ่ม</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
            <!-- Button trigger modal-->

            <!-- Modal-->
            <div class="modal fade" id="createRichMenuImage" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <form action="{{ route('admin.rich-menu.create') }}" id="formCreateRichMenu" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" name="brand_id" value="{{ $brand->id }}">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">เพิ่มริชเมนู</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="">RICH MENU JSON</label>
                                        <textarea name="rich_menu_data" class="form-control" id="" cols="30" rows="10">

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
    {!! JsValidator::formRequest('App\Http\Requests\RichMenuRequest', '#formCreateRichMenu') !!}
@endsection
