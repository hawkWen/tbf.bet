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

                                หน้าจอการถอนเงิน</a>
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
                            หน้าจอการถอนเงิน
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('agent.withdraw') }}" class="btn btn-danger"> <i class="fa fa-credit-card"></i>
                        ถอนเงิน</a>
                    <a href="{{ route('agent.withdraw.history') }}" class="btn btn-secondary"> <i
                            class="fa fa-history"></i> ประวัติการถอนเงิน</a>
                </div>
                <!--begin::Body-->
                <div class="card-body">
                    <div class="pull-right">
                    </div>
                </div>

                <div class="card-body" id="withdrawLists">
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
            <!-- Button trigger modal-->
            <!-- Modal-->
            <div class="modal fade" modal id="withdrawHoldListModal" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('agent.withdraw.store') }}" method="post" id="formWithdrawHold"
                            enctype="multipart/form-data">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title" id="exampleModalLabel">ฟอร์มถอนเงินรายการที่ค้าง</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="bg-success">
                                                    <td>เลือก</td>
                                                    <td>รายการค้าง</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="">บัญชีธนาคารลูกค้า</label>
                                        <input type="text" class="form-control" name="bank_account" id="bank_account">
                                    </div>
                                    <div class="col-lg-12 mt-4 mb-4">
                                        <label for="">โบนัส</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="promotion_id" id="promotion1"
                                                value="0" checked>
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
                                            ถอนเงิน
                                        </button>
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
        var soundWithdraw = new Audio('{{ asset('sound/withdraw.mp3') }}');

        // request permission on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (!Notification) {
                alert('Desktop notifications not available in your browser. Try Chromium.');
                return;
            }

            if (Notification.permission !== 'granted')
                Notification.requestPermission();
        });

        // jQuery plugin to prevent double submission of forms
        jQuery.fn.preventDoubleSubmission = function() {
            $(this).on('submit', function(e) {
                var $form = $(this);

                if ($form.data('submitted') === true) {
                    // Previously submitted - don't submit again
                    e.preventDefault();
                } else {
                    // Mark it so that the next submit can be ignored
                    $form.data('submitted', true);
                }
            });

            // Keep chainability
            return this;
        };

        $(function() {
            $("form").each(function() {
                $(this).preventDoubleSubmission();
            });
        });

        function notifyMe() {
            if (Notification.permission !== 'granted')
                Notification.requestPermission();
            else {
                var notification = new Notification('Notification title', {
                    // icon: '{{ asset('images/fastX.png') }}',
                    body: 'Hey there! You\'ve been notified!',
                });
                notification.onclick = function() {
                    // window.open('http://stackoverflow.com/a/13328397/1269037');
                };
            }
        }

        var ajax_inprocess = false;

        $(document).ajaxStart(function() {
            ajax_inprocess = true;
        });

        $(document).ajaxStop(function() {
            ajax_inprocess = false;
        });

        //Snippet from live search function
        if (ajax_inprocess == true) {
            request.abort();
        }

        $(function() {
            withdrawList();
            notifyWithdraw();
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
                    notifyWithdraw();
                    withdrawList();
                }
            }, 5000);
        });
        // $(document).on('hide.bs.modal','[modal]',function(){
        //     $('.modal-backdrop').remove();
        // });

        function withdrawList() {

            $('#withdrawLists').load('/withdraw/lists', function(response, status, xhr) {
                $('.modal-backdrop').remove();
                renderInput();

                $("form").each(function() {
                    console.log($(this));
                    $(this).preventDoubleSubmission();
                });
            });

        }

        function notifyWithdraw() {

            var withdrawCount = $('#withdrawCount').val();

            $.get('{{ route('agent.withdraw.notify') }}', function(r) {

                if (r.count > withdrawCount) {
                    // soundWithdraw.play();
                    var notification = new Notification('คุณมีการแจ้งถอนใหม่จากระบบ ' + r.brand.name, {
                        // icon: '{{ asset('images/fastX.png') }}',
                        body: 'คลิกเพื่อตรวจสอบ',
                    });
                    notification.onclick = function() {
                        window.open('https://agent.casinoauto.io/withdraw');
                    };
                }

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
    </script>
@endsection
