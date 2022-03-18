@extends('layouts.agent')

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

                                จัดการแบรนด์</a>
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
                <!--end::Actions-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-fluid-->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('agent.brand.update') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="update_type" value="1">
                        <input type="hidden" name="brand_id" value="{{ $brand->id }}" />
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-3 col-2">
                                        <label for="">โลโก้แบรนด์</label>
                                        <br>
                                        <div class="image-input image-input-outline" id="kt_image">
                                            <div class="image-input-wrapper"
                                                style="background-image: url({{ $brand->logo_url }})"></div>
                                            <label
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="change" data-toggle="tooltip" title=""
                                                data-original-title="Upload Logo">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="logo" accept=".png, .jpg, .jpeg, .gif" />
                                                <input type="hidden" name="logo_remove" />
                                            </label>
                                            <span
                                                class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="cancel" data-toggle="tooltip" title="Cancel Logo">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-10">
                                        <div class="col-12">
                                            <label for="">ชื่อแบรนด์</label>
                                            <input type="text" class="form-control" name="name" input-type="character"
                                                value="{{ $brand->name }}" disabled>
                                        </div>
                                        <div class="col-12">
                                            <label for="">LINE_ID (ไม่ต้องใส่แอด)</label>
                                            <input type="text" class="form-control" name="line_id" input-type="character"
                                                value="{{ $brand->line_id }}">
                                        </div>
                                        <div class="col-12">
                                            <label for="">เบอร์โทรศัพท์ติดต่อ</label>
                                            <input type="text" class="form-control" name="telephone"
                                                input-type="telephone" value="{{ $brand->telephone }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="">Sub Domain</label>
                                        <input type="text" class="form-control" name="subdomain" input-type="character"
                                            value="{{ $brand->subdomain }}" readonly disabled>
                                    </div>
                                    <div class="col-lg-9">
                                        <label for="">&nbsp;</label>
                                        <input type="text" value=".casinoauto.io" class="form-control" readonly disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="">Agent Username</label>
                                        <input type="text" class="form-control" name="agent_username"
                                            value="{{ $brand->agent_username }}" readonly disabled>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Agent Password</label>
                                        <input type="text" class="form-control" name="agent_password"
                                            value="{{ $brand->agent_password }}" readonly disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row">

                                    <div class="col-lg-12">
                                        <label for="">ถอนอัตโนมัติในวงเงินไม่เกิน </label>
                                        <input type="text" class="form-control" name="withdraw_auto_max"
                                            input-type="money_decimal" placeholder="10,000"
                                            value="{{ $brand->withdraw_auto_max }}">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>ประเภทการเติมเงิน</label>
                                            <div class="radio-list">
                                                <label class="radio">
                                                    <input type="radio" name="type_deposit" value="1"
                                                        @if ($brand->type_deposit == 1) checked @endif>
                                                    <span></span>
                                                    บอทเติม
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" name="type_deposit" value="2"
                                                        @if ($brand->type_deposit == 2) checked @endif>
                                                    <span></span>
                                                    โอนสลิป
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group pull-right">
                            <button type="submit" class="btn btn-primary font-weight-bolder btn-sm">
                                <i class="fa fa-save"></i>บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-fluid-->

        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('agent.brand.update') }}" method="post">
                        <input type="hidden" name="update_type" value="2">
                        <input type="hidden" name="brand_id" value="{{ $brand->id }}" />
                        <h4 class="pb-2"> <i class="fa fa-toolbox"></i> เปิด/ปิด ระบบ </h4>
                        <div class="row">
                            <div class="col-lg-2 p-5">
                                <div class="form-group">
                                    <div class="radio-list">
                                        <label class="radio">
                                            <input type="radio" name="status" value="1"
                                                @if ($brand->status == 1) checked @endif>
                                            <span></span>
                                            เปิด
                                        </label>
                                        <label class="radio">
                                            <input type="radio" name="status" value="0"
                                                @if ($brand->status == 0) checked @endif>
                                            <span></span>
                                            ปิด
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label for="">ข้อความปิดระบบ</label>
                                    <textarea name="maintenance" class="form-control" id="" cols="30" rows="10">{{ $brand->maintenance }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group pull-right">
                            <button type="submit" class="btn btn-primary font-weight-bolder btn-sm">
                                <i class="fa fa-save"></i>บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-fluid-->

        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('agent.brand.update') }}" method="post">
                        <input type="hidden" name="update_type" value="2">
                        <input type="hidden" name="brand_id" value="{{ $brand->id }}" />
                        <h4> <i class="fab fa-line"></i> LINE NOTIFY TOKEN </h4>
                        <div class="row">
                            <div class="col-lg-4">
                                <input type="text" class="form-control" name="line_notify_token"
                                    value="{{ $brand->line_notify_token }}" required>
                                <br>
                                <div class="form-group pull-right">
                                    <button type="submit" class="btn btn-primary font-weight-bolder btn-sm">
                                        <i class="fa fa-save"></i>บันทึก</button>
                                </div>
                                <a href="https://www.makewebeasy.com/th/blog/line-notification/">วิธีตั้งค่าระบบแจ้งเตือน
                                    ผ่านทาง Line Notification API</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-fluid-->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="form-group pull-right">
                        <label>สถานะ</label>
                        <div class="radio-list">
                            <label class="radio">
                                <input type="radio" name="status_rank" value="1"
                                    @if ($brand->status_rank == 1) checked @endif onchange="updateStatusRank(1)">
                                <span></span>
                                เปิด
                            </label>
                            <label class="radio">
                                <input type="radio" name="status_rank" value="0"
                                    @if ($brand->status_rank == 0) checked @endif onchange="updateStatusRank(0)">
                                <span></span>
                                ปิด
                            </label>
                        </div>
                    </div>
                    <h3>ระบบ Ranking (จัดอันดับ)</h3>
                    <form action="{{ route('agent.brand.update-rank') }}" method="post">
                        <div class="clearfix"></div>
                        <hr>
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm"> <i class="fa fa-save"></i>
                                บันทึกการตั้งค่า</button>
                        </div>
                        <h3 class="text-center">ตั้งค่า rank ต่างๆ</h3>
                        <hr>
                        <input type="hidden" name="brand_id" value="{{ $brand->id }}" />
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>rank</th>
                                    <th>เงื่อนไข</th>
                                    <th>รางวัล</th>
                                    <th>เงื่อนไข หรือ คำอธิบายเพิ่มเติม</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brand->ranks as $rank)
                                    <tr>
                                        <td width="150">
                                            <input type="hidden" name="rank_ids[]" value="{{ $rank->id }}">
                                            <img src="{{ asset('images/ranking/' . $rank->rank . '.png') }}"
                                                class="img-fluid img-center" width="150" alt="">
                                            <p class="text-center">{{ $rank->rank }}</p>
                                        </td>
                                        <td width="300">
                                            ยอดเติมเงินขั้นต่ำ
                                            <input type="text" name="min[{{ $rank->id }}]" class="form-control"
                                                input-type="money_decimal" value="{{ $rank->min }}">
                                        </td>
                                        <td width="300">
                                            เครดิตฟรี
                                            <input type="text" name="reward[{{ $rank->id }}]" class="form-control"
                                                input-type="money_decimal" value="{{ $rank->reward }}">
                                        </td>
                                        <th width="300">
                                            <textarea name="description[{{ $rank->id }}]" name="description" class="form-control">
                                                                                                                                        {{ $rank->description }}
                                                                                                                                    </textarea>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    {!! JsValidator::formRequest('App\Http\Requests\BrandRequest', '#formCreateBrand') !!}

    <script>
        // Example 4
        var avatar_4 = new KTImageInput('kt_image');

        function updateStatus(type, bank_account_id) {

            var status = ($('#status_' + type).is(':checked')) ? 1 : 0;

            $.post('{{ route('agent.bank-account.update-status') }}', {
                status: status,
                type: type,
                bank_account_id: bank_account_id
            }, function(r) {
                // location.reload();
            });

        }

        function updateStatusRank(status) {

            $.post('{{ route('agent.brand.update-status-rank') }}', {
                status: status
            }, function(r) {
                // location.reload();
            });

        }
    </script>
@endsection
