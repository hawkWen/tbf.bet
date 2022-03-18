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
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('agent') }}" class="text-muted">ภาพรวม</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-dark">

                                ข้อความประกาศ</a>
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
                    <!--begin::Actions-->
                    <a href="#" class="btn btn-primary font-weight-bolder btn-sm" data-toggle="modal"
                        data-target="#createAnnoucementModal">
                        <i class="fa fa-plus"></i>เพิ่มข้อความประกาศ</a>
                    <!--end::Actions-->
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
                            <i class="fa fa-bullhorn mr-2"></i>
                            ข้อความประกาศ
                        </h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="100">ลำดับที่</th>
                                <th>วันที่เพิ่ม</th>
                                <th>เนื้อหา</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($annoucements->sortByDesc('created_at') as $key => $annoucement)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td width="150">{{ $annoucement->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <h4> <u>{{ $annoucement->title }}</u></h4>
                                        <p>{{ $annoucement->content }}</p>
                                    </td>
                                    <td width="200" align="center">
                                        <button type="button" class="btn btn-warning"
                                            data-target="#editAnnoucementModal_{{ $annoucement->id }}"
                                            data-toggle="modal">
                                            แก้ไข
                                        </button>
                                        <button type="button" class="btn btn-danger"
                                            onclick="deleteAnnoucement({{ $annoucement->id }})">
                                            ลบ
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editAnnoucementModal_{{ $annoucement->id }}"
                                    data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg " role="document">
                                        <form action="{{ route('support.annoucement.update') }}" method="post">
                                            <input type="hidden" class="form-control" name="annoucement_id"
                                                value="{{ $annoucement->id }}">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning ">
                                                    <h5 class="modal-title text-white" id="exampleModalLabel">
                                                        เพิ่มข้อความประกาศ</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <i aria-hidden="true" class="ki ki-close"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <input type="text" class="form-control" name="title"
                                                                placeholder="หัวเรื่อง"
                                                                value="{{ $annoucement->title }}" />
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <textarea name="content" id="content" cols="30" rows="20"
                                                                class="form-control"
                                                                placeholder="เนื้อหาที่ประกาศ">{{ $annoucement->content }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold"
                                                        data-dismiss="modal">ยกเลิก</button>
                                                    <button type="submit"
                                                        class="btn btn-warning font-weight-bold">บันทึก</button>
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
            <div class="modal fade" id="createAnnoucementModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg " role="document">
                    <form action="{{ route('support.annoucement.store') }}" method="post">
                        <div class="modal-content">
                            <div class="modal-header bg-primary ">
                                <h5 class="modal-title text-white" id="exampleModalLabel">เพิ่มข้อความประกาศ</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="title" placeholder="หัวเรื่อง" />
                                    </div>
                                    <div class="col-lg-12">
                                        <textarea name="content" id="content" cols="30" rows="20" class="form-control"
                                            placeholder="เนื้อหาที่ประกาศ"></textarea>
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

    {!! JsValidator::formRequest('App\Http\Requests\PromotionRequest', '#formCreateDeposit') !!}
    <script>
        function deleteAnnoucement(annoucement_id) {
            if (confirm('ยืนยันการลบ ? ')) {
                $.get('/annoucement/delete/' + annoucement_id, function() {
                    location.reload();
                })
            }
        }
    </script>

@endsection
