@extends('layouts.frontend3')

@section('css')
    
@endsection

@section('content')
<input type="hidden" class="form-control" name="brand_id" id="brand_id" value="{{$brand->id}}">
<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="pb-3 pr-2">
        <div class="">
            <h2 class="float-left mb-0 text-white">{{$brand->name}}</h2>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="content-body">
        <form action="{{route('gclub.member.withdraw.store',$brand->subdomain)}}" method="post" enctype="multipart/form-data" id="formWithdrawManual">
            <input type="hidden" name="customer_id" value="{{$customer->id}}">
            <h4>ถอนเงินเข้าบัญชีธนาคารของคุณ</h4>
            <hr>
            <div class="alert alert-info">

                <p class="mt-2">
                    บัญชีของคุณ
                </p>
                <p class="">
                    <img src="{{asset($customer->bank->logo)}}" alt="" width="30"> {{$customer->name}} {{$customer->bank_account}}
                </p>
            </div>
            <h4>รายละเอียดการถอน</h4>
            <hr>
            <p><b>จำนวนเงินที่ถอนได้: </b> <span class="text-success">{{number_format(Auth::guard('customer')->user()->credit,2)}}</span> </p>
            <p><b>ถอนขั้นต่ำ: </b> <span class="text-danger"> {{number_format($brand->withdraw_min,2)}} </span></p>
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    @if($customer->credit < $brand->withdraw_min)
                                    <div class="pull-right">
                                        <p class="text-danger">{{$brand->withdraw_min}}0</p>
                                    </div>
                                    @endif
                                    <h6 class="subtitle mb-0">จำนวนเงินที่ต้องการถอน</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="text" class="form-control dark" name="amount" input-type="money_decimal" class="form-control" placeholder="{{$brand->withdraw_min}}">
                            @if($customer->username != '')
                            <div class="pull-right mt-3">
                                <button type="submit" class="btn btn-success btn-sm mb-2 mx-auto rounded">
                                    <i class="fa fa-check"></i>
                                    ถอนเงิน</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('javascript')

    <script>

        // jQuery plugin to prevent double submission of forms
        jQuery.fn.preventDoubleSubmission = function() {
            $(this).on('submit',function(e){
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
            $('#formWithdrawManual').preventDoubleSubmission();
        });

        function checkCredit() {

            $.get('{{route('gclub.member.get-credit', $brand->subdomain)}}', function(r) {
                if(r.code == 0) {
                    $.notify({
                        // options
                        message: r.message
                    },{
                        // settings
                        type: (r.code == 0) ? 'warning' : 'info',
                        animate: {
                            enter: 'animated fadeInDown',
                            exit: 'animated fadeOutUp'
                        },
                        placement: {
                            from: "top",
                            align: "center"
                        },
                    });
                } else {
                    $.notify({
                        // options
                        message: r.message
                    },{
                        // settings
                        type: (r.code == 0) ? 'warning' : 'info',
                        animate: {
                            enter: 'animated fadeInDown',
                            exit: 'animated fadeOutUp'
                        },
                        placement: {
                            from: "top",
                            align: "center"
                        },
                    });
                    location.reload();
                }
            })

        }

    </script>
    
@endsection