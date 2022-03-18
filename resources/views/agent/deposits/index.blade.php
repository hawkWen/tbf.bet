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
                            <a href="{{ route('agent') }}" class="text-muted">ภาพรวม</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-dark">

                                หน้าจอการเติมเงิน</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
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
                            หน้าจอการเติมเงิน
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('agent.deposit') }}" class="btn btn-primary"> <i class="fa fa-hand-holding-usd"></i>
                        เติมเงิน</a>
                    <a href="{{ route('agent.deposit.history') }}" class="btn btn-secondary"> <i
                            class="fa fa-history"></i>
                        ประวัติการเติมเงิน</a>
                    <div class="pull-right">
                        <div class="">
                            <div class="form-group">
                                <label>ประเภทการเติมเงิน</label>
                                <div class="radio-list">
                                    <label class="radio">
                                        <input type="radio" name="type_deposit" value="1"
                                            @if ($brand->type_deposit == 1) checked @endif
                                            onchange="changeTypeDeposit({{ $brand->id }},'1')">
                                        <span></span>
                                        บอทเติม
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="type_deposit" value="2"
                                            @if ($brand->type_deposit == 2) checked @endif
                                            onchange="changeTypeDeposit({{ $brand->id }},'2')">
                                        <span></span>
                                        โอนสลิป
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--begin::Body-->

                <div class="card-body">
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#depositHoldListModal">
                            เติมเงินรายการที่ค้าง
                            <span class="symbol symbol-35 symbol-light-danger ml-3">
                                <span class="symbol-label font-size-h5 font-weight-bold">
                                    {{ $bank_account_transactions->count() }}
                                </span>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="card-body" id="depositLists">
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
            <!-- Button trigger modal-->
            <!-- Modal-->
            <div class="modal fade" modal id="depositHoldListModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-extra-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('agent.deposit.store') }}" method="post" id="formDepositHold"
                            enctype="multipart/form-data">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-white" id="exampleModalLabel">ฟอร์มเติมเงินรายการที่ค้าง</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="bg-success">
                                                    <td>เลือก</td>
                                                    <td>รายการค้าง</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($bank_account_transactions as $bank_account_transaction)
                                                    <tr>
                                                        <td align="center" class="pt-5">
                                                            <label for="">
                                                                <input type="radio" class="pt-2"
                                                                    name="bank_transaction_id"
                                                                    value="{{ $bank_account_transaction->id }}">
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <p class="pull-right">
                                                                <b>สถานะ : </b>

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
                                                                @elseif($bank_account_transaction->status == 9)
                                                                    <span class="text-danger mr-2">
                                                                        <i class="fa fa-times"></i>
                                                                        ลูกค้าออนไลน์อยู่
                                                                    </span>
                                                                @endif
                                                            </p>
                                                            <p><b>เวลาโอน :</b>
                                                                {{ $bank_account_transaction->created_at }}
                                                            </p>
                                                            {{-- @if ($bank_account_transaction->bankAccount) --}}
                                                            <p><b>ธนาคารที่รับ : </b>
                                                                {{ $bank_account_transaction->bankAccount->bank->name }}
                                                                {{ $bank_account_transaction->bankAccount->name }}
                                                                {{ $bank_account_transaction->bankAccount->account }}
                                                                {{-- @endif --}}
                                                            </p>
                                                            <p><b>บัญชีธนาคารลูกค้า : </b>
                                                                {{ $bank_account_transaction->code_bank }}
                                                                {{ $bank_account_transaction->bank_account }}
                                                            </p>
                                                            <p><b>จำนวนเงิน : </b>
                                                                {{ number_format($bank_account_transaction->amount, 2) }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="col-lg-12">
                                            <label for="">บัญชีธนาคารลูกค้า</label>
                                            <select class="form-control select2" id="kt_select2_6" name="bank_account">
                                            </select>
                                        </div>
                                        <div class="col-lg-12 mt-4 mb-4">
                                            <label for="">โบนัส</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="promotion_id"
                                                    id="promotion1" value="0" checked>
                                                <label class="form-check-label" for="promotion1">
                                                    ไม่รับโบนัส
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 ">
                                            <label for="">สลิปการโอนเงิน</label>
                                            <input type="file" class="form-control" name="slip" />
                                        </div>
                                        <div class="col-lg-12">
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            ผู้ทำรายการ {{ Auth::user()->name }}
                                            <button type="submit" class="btn btn-primary pull-right">
                                                เติมเงิน
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
@endsection

@section('javascript')
    {!! JsValidator::formRequest('App\Http\Requests\DepositHoldRequest', '#formDepositHold') !!}

    <script>
        var soundDeposit = new Audio('{{ asset('sound/deposit.mp3') }}');

        // request permission on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (!Notification) {
                alert('Desktop notifications not available in your browser. Try Chromium.');
                return;
            }

            if (Notification.permission !== 'granted')
                Notification.requestPermission();
        });

        // // Example 4
        // var avatar_4 = new KTImageInput('kt_image');

        // var promotions = $('#promotions').val().replace('[','').replace(']','').split(',');

        // $.each(promotions, function(k,v) {
        //     new KTImageInput('kt_image_' + v);
        // });

        $(function() {
            $('#kt_select2_6').select2({
                placeholder: 'ค้นหาลูกค้าจากเลขที่บัญชี',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '{{ route('agent.deposit.find-customer') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data,
                            pagination: {
                                // more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function(markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatRepo, // omitted for brevity, see the source of this page
                templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
            });
            depositList();
            notifyDeposit();
            var ajax = $.ajaxSetup({
                complete: function(xhr, textStatus) {
                    // will be raised whenever an AJAX request completes (success or failure)
                },
                success: function(result) {
                    // will be raised whenever an AJAX request succeeds
                },
            });
            setInterval(function() {
                if ($("[modal]").is(':visible')) {
                    console.log('stop');
                } else {
                    notifyDeposit();
                    depositList();
                }
            }, 5000);
        });

        function formatRepo(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.name + "";
            if (repo.username) {
                markup += " ( " + repo.username + " )";
            } else {
                markup += ' (ยังไม่ได้รับยูส)';
            }
            markup += "<div class='select2-result-repository__statistics'>" +
                "<div class='select2-result-repository__forks'><i class='fa fa-university'></i>  " + repo.bank.name +
                " </div>" +
                "<div class='select2-result-repository__stargazers'><i class='fa fa-credit-card'></i>  " + repo
                .bank_account + " </div>" +
                "</div> </div>" +
                "</div></div>";
            return markup;
        }

        function formatRepoSelection(repo) {
            // if(repo) {
            //     $result = repo.username + ' (' + repo.name + ')';
            // } else {
            //     $result = 'ค้นหาลูกค้าจากเลขที่บัญชี';
            // }
            return repo.username + ' (' + repo.name + ')' || 'ค้นหาลูกค้าจากเลขที่บัญชี';
        }
        $(document).on('hide.bs.modal', '[modal]', function() {
            $('.modal-backdrop').remove();
        });

        function depositList() {

            $('#depositLists').load('/deposit/lists', function(response, status, xhr) {
                $('.modal-backdrop').remove();
                renderInput();
            });
        }

        function updateStatus(promotion_id, status) {

            $.post('{{ route('agent.promotion.update-status') }}', {
                promotion_id: promotion_id,
                status: status
            }, function(r) {
                location.reload();
            });

        }

        function changeTypeDeposit(brand_id, type_deposit) {

            $.post('{{ route('agent.deposit.update-type-deposit') }}', {
                brand_id: brand_id,
                type_deposit: type_deposit
            }, function() {
                location.reload();
            });

        }

        function submitDepositManual(customer_deposit_id) {

            $('#btnSubmit_' + customer_deposit_id).html('ระบบกำลังเติมเงินกรุณารอซักครู่ค่ะ ...');

            $('#btnSubmit_' + customer_deposit_id).attr('disabled', true);

            $.post('{{ route('agent.deposit.manual') }}', {
                customer_deposit_id: customer_deposit_id
            }, function(r) {

                if (r === true) {
                    location.reload();
                } else {

                    $('#btnSubmit_' + customer_deposit_id).html('ยืนยัน');

                    $('#btnSubmit_' + customer_deposit_id).attr('disabled', false);

                }

            });

        }

        function cancelDepositManual(customer_deposit_id) {

            $('#btnSubmit_' + customer_deposit_id).html('ระบบกำลังยกเลิกใบงาน ...');

            $('#btnSubmit_' + customer_deposit_id).attr('disabled', true);

            var remark = $('#remark_' + customer_deposit_id).val();

            $.post('{{ route('agent.deposit.cancel') }}', {
                customer_deposit_id: customer_deposit_id,
                remark: remark
            }, function(r) {

                if (r === true) {
                    location.reload();
                } else {

                    $('#btnSubmit_' + customer_deposit_id).html('ยืนยัน');

                    $('#btnSubmit_' + customer_deposit_id).attr('disabled', false);

                }

            });
        }


        function notifyDeposit() {

            var depositCount = $('#depositCount').val();

            $.get('{{ route('agent.deposit.notify') }}', function(r) {

                if (r.count > depositCount) {
                    soundDeposit.play();
                    var notification = new Notification('คุณมีการแจ้งเติมเงินจากระบบ ' + r.brand.name, {
                        // icon: '{{ asset('images/fastX.png') }}',
                        body: 'คลิกเพื่อตรวจสอบ',
                    });
                    notification.onclick = function() {
                        window.open('https://agent.casinoauto.io/deposit');
                    };
                }

            });

        }
    </script>
@endsection
