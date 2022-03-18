@extends('layouts.uking')

@section('css')
    
@endsection

@section('content')

    
<div class="main-container">
    <div class="container mb-2">
        <div class="row mb-2">
            <div class="col-6">
                <a href="{{route('uking.member.deposit',$brand->subdomain)}}" 
                    class="btn btn-outline-default px-2 btn-block rounded"><i class="fa fa-hand-holding-usd"></i> เติมเงิน</a>
            </div>
            <div class="col-6">
                <a href="{{route('uking.member.withdraw',$brand->subdomain)}}" 
                    class="btn btn-outline-default px-2 btn-block rounded"><i class="fa fa-credit-card"></i> ถอนเงิน</a>
            </div>
        </div>
    </div>

    <div class="container mb-2">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p>ไอดีเข้าเล่นเกมส์</p>
                        <div class="">
                            <p class=" text-dark text-shadow mt-4">
                                <b>Username:</b> {{Auth::guard('customer')->user()->username}}
                                <button href="javascript:void(0);" class="btn pull-right pb-2 text-white" data-clipboard-text="{{Auth::guard('customer')->user()->username}}" onclick="alert('{{Auth::guard('customer')->user()->username}}')" style="border: none;background-color: transparent;">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </p>
                            <hr>
                            <p class=" text-dark text-shadow">
                                <b>Password:</b> {{Auth::guard('customer')->user()->password_generate}}
                                <button href="javascript:void(0);" class="btn pull-right pb-2 text-white" data-clipboard-text="{{Auth::guard('customer')->user()->password_generate}}" onclick="alert('{{Auth::guard('customer')->user()->password_generate}}')" style="border: none;background-color: transparent;">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-4">
        <div class="card">
            <div class="card-body text-center ">
                <div class="row justify-content-equal no-gutters">
                    <div class="col-4 col-md-2 mb-3">
                        <a href="{{route('uking.member.history',$brand->subdomain)}}" class="icon icon-50 rounded-circle mb-1 bg-default-light text-default" style="font-size: 18px;">
                            <i class="fa fa-file"></i>
                        </a>
                        <p class="text-secondary"><small>ประวัติการทำรายการ</small></p>
                    </div>
                    @if(Auth::guard('customer')->user()->status_deposit == 1)
                        <div class="col-4 col-md-2 mb-3">
                            <a href="{{route('uking.member.invite', $brand->subdomain)}}" class="icon icon-50 rounded-circle mb-1 bg-default-light text-default" style="font-size: 18px;">
                                <i class="fa fa-users"></i>
                            </a>
                            <p class="text-secondary"><small>แนะนำเพื่อน</small></p>
                        </div>
                    @endif
                    <div class="col-4 col-md-2 mb-3">
                        <a href="{{route('uking.member.connect', $brand->subdomain)}}" class="icon icon-50 rounded-circle mb-1 bg-default-light text-default" style="font-size: 18px;">
                            <i class="fab fa-line text-success"></i>
                        </a>
                        <p class="text-secondary"><small>เชื่อมต่อบัญชี LINE</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($promotions->count() > 0)
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"> <i class="fa fa-tags"></i> เลือกโปรโมชั่น</h6>
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="list-group list-group-flush border-top border-color">
                        @foreach($promotions as $promotion)
                            <div  class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <img src="{{asset($promotion->img_url)}}" alt="" class="img-fluid img-center pb-2 pt-2" width="100">
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">{{$promotion->name}}</h6>
                                        <p class="text-secondary">ขั้นต่ำ {{$promotion->min}}</p>
                                        <button class="btn btn-warning  btn-sm pull-right" onclick="changePromotion({{Auth::guard('customer')->user()->id}},{{$promotion->id}},'{{$promotion->name}}')">
                                            <i class="fa fa-hand-pointer"></i>
                                            เลือกโปรนี้</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
    

@if(Auth::guard('customer')->user()->line_user_id === null)
    <!-- Modal-->
    <div class="modal fade mt-5" id="lineNotifyModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-white" id="exampleModalLabel">กรุณากดที่กระดิ่ง เพื่อรับการแจ้งเตือน LINE</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="{{route('uking.member.connect', $brand->subdomain)}}" target="_blank">
                        <img src="{{asset('images/line-notify.png')}}" width="150" class="img-center img-fluid" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@section('javascript')

    <script>

        $(function() {
            $('#lineNotifyModal').modal('show');
            var clipboard = new ClipboardJS('.btn');
            clipboard.on('success', function(e) {
                console.info('Action:', e.action);
                console.info('Text:', e.text);
                console.info('Trigger:', e.trigger);

                e.clearSelection();
            });
            clipboard.on('error', function(e) {
                console.error('Action:', e.action);
                console.error('Trigger:', e.trigger);
            });
            console.log(clipboard);
        });
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
            $('#formChangePassword').preventDoubleSubmission();
        });

        function changePromotion(customer_id, promotion_id, promotion_name) {

            if(confirm('ยืนยันที่จะเลือกโปรโมชั่น "' + promotion_name + '"')) {
                $.post('{{route('uking.update-promotion')}}', {customer_id: customer_id,promotion_id: promotion_id}, function(r) {
                    $('#alertPromotion').fadeIn();
                    if(r.status == true) {
                        $.notify(promotion_name,'info');
                        setTimeout(function() {
                            $('#alertPromotion').fadeOut();
                        },3000);
                    }
                });
            }

        }

        function checkCredit() {

            $('#aCheckCredit').attr('disabled', true);

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
                    // $.notify({
                    //     // options
                    //     message: r.message
                    // },{
                    //     // settings
                    //     type: (r.code == 0) ? 'warning' : 'info',
                    //     animate: {
                    //         enter: 'animated fadeInDown',
                    //         exit: 'animated fadeOutUp'
                    //     },
                    //     placement: {
                    //         from: "top",
                    //         align: "center"
                    //     },
                    // });
                    location.reload();
                }
            })

        }

    </script>
    
@endsection