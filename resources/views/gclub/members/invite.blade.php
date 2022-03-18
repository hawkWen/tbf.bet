@extends('layouts.frontend3')

@section('css')

@endsection

@section('content')


    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="pb-3 pr-2">
            <div class="">
                <h2 class="float-left mb-0 text-white">{{ $brand->name }}</h2>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="content-body">
            <h2 class="text-center">ระบบแนะนำเพื่อน</h2>
            <img src="{{ asset('frontend3/app-assets/images/illustration/marketing.svg') }}" width="200"
                class="img-fluid img-center" alt="">
            <h4 class="text-center mt-2">ลิงค์แนะนำเพื่อนของคุณ</h4>
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="input-group form-password-toggle mb-2">
                        <input type="text" class="form-control" value="{{ $customer->invite_url }}" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text cursor-pointer">
                                <i class="fa fa-copy"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-employee-task">
                <div class="card-header">
                    <h4 class="card-title">รายชื่อเพื่อนของคุณ</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="employee-task d-flex justify-content-between align-items-center pb-2">
                                <div class="media">
                                    <div class="media-body my-auto">
                                        <h6 class="mb-0"> <i class="fa fa-user"></i> Afct123123</h6>
                                        <small>ออนไลน์ล่าสุด</small>
                                        <small class="text-muted mr-75">9hr 20m</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="employee-task d-flex justify-content-between align-items-center pb-2">
                                <div class="media">
                                    <div class="media-body my-auto">
                                        <h6 class="mb-0"> <i class="fa fa-user"></i> Afct123123</h6>
                                        <small>ออนไลน์ล่าสุด</small>
                                        <small class="text-muted mr-75">9hr 20m</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="employee-task d-flex justify-content-between align-items-center pb-2">
                                <div class="media">
                                    <div class="media-body my-auto">
                                        <h6 class="mb-0"> <i class="fa fa-user"></i> Afct123123</h6>
                                        <small>ออนไลน์ล่าสุด</small>
                                        <small class="text-muted mr-75">9hr 20m</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="employee-task d-flex justify-content-between align-items-center pb-2">
                                <div class="media">
                                    <div class="media-body my-auto">
                                        <h6 class="mb-0"> <i class="fa fa-user"></i> Afct123123</h6>
                                        <small>ออนไลน์ล่าสุด</small>
                                        <small class="text-muted mr-75">9hr 20m</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="employee-task d-flex justify-content-between align-items-center pb-2">
                                <div class="media">
                                    <div class="media-body my-auto">
                                        <h6 class="mb-0"> <i class="fa fa-user"></i> Afct123123</h6>
                                        <small>ออนไลน์ล่าสุด</small>
                                        <small class="text-muted mr-75">9hr 20m</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="employee-task d-flex justify-content-between align-items-center pb-2">
                                <div class="media">
                                    <div class="media-body my-auto">
                                        <h6 class="mb-0"> <i class="fa fa-user"></i> Afct123123</h6>
                                        <small>ออนไลน์ล่าสุด</small>
                                        <small class="text-muted mr-75">9hr 20m</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

    <script>
        $(function() {
            new ClipboardJS('.btn');
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
            $('#formInviteStore').preventDoubleSubmission();
        });

    </script>

@endsection
