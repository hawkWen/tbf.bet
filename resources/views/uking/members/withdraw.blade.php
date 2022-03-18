@extends('layouts.uking')

@section('css')
    
@endsection

@section('content')

<form action="{{route('uking.member.withdraw.store',$brand->subdomain)}}" method="post" enctype="multipart/form-data" id="formWithdrawManual">
    <div class="main-container" style="margin-bottom: 100px"> 
        <h5 class="pl-3 pt-1"> <i class="fa fa-credit-card "></i> ถอนเงิน</h5>
        <hr> 
        <div class="container mb-2">
            <small>กดถอนเงินแล้วระบบจะโอนไปยังบัญชีของท่านโดยอัตโมนัติ</small>
            <div class="card">
                <div class="card-body">
                    <div class="card border-0 bg-default text-white" style="background-color: {{$customer->bank->bg_color}};color: {{$customer->bank->font_color}};border-radius: 5px;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-auto">
                                    <img src="{{asset($customer->bank->logo)}}" alt="">
                                </div>
                                <div class="col pl-0">
                                    <h6 class="mb-1">{{$customer->bank->name}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="mb-0 mt-3">{{$customer->bank_account}}</h5>
                            <p class="small">เลขที่บัญชี</p>
                            <div class="col-auto align-self-center text-right">
                                <p class="mb-0">{{$customer->name}}</p>
                                <p class="small">ชื่อบัญชี</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mb-2">
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
                        <button type="submit" href="thank_you.html" class="btn btn-default btn-sm mb-2 mx-auto rounded">
                            <i class="fa fa-check"></i>
                            ถอนเงิน</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
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

            $.get('{{route('uking.member.get-credit', $brand->subdomain)}}', function(r) {
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