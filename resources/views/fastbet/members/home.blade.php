@extends('layouts.frontend3')

@section('css')
    
@endsection

@section('content')

<div class="content-overlay"></div>
<div class="content-wrapper">
    <div class="pb-3 pr-2">
        <div class="">
            <h2 class="float-left mb-0 text-white">{{$brand->name}}</h2>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="content-body">
        <!-- Kick start -->
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <div class="card card-congratulation-medal">
                    <div class="card-body">
                        <h2>เครดิตปัจจุบันของคุณ</h2>
                        <p class="card-text font-small-3">อัพเดทล่าสุดเมื่อ: 
                            @if(isset(Auth::guard('customer')->user()->last_update_credit))
                            {{Auth::guard('customer')->user()->last_update_credit->format('d/m/Y H:i:s')}}
                        @endif</p>
                        <h3 class="mb-75 mt-2 pt-50">
                            <a href="javascript:void(0);">{{number_format(Auth::guard('customer')->user()->credit,2)}} credit</a>
                        </h3>
                        {{-- <a href="{{route($brand->game->name.'.member.get-credit', $brand->subdomain)}}" type="button" class="btn btn-info waves-effect waves-float waves-light text-dark"> <i class="fa fa-sync"></i> รีเฟรชเครดิต</a> --}}
                        <img src="{{asset('frontend3/images/casino.png')}}" class="congratulation-chip" class="img-fluid" alt="Medal Pic" width="120">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <!-- Page layout -->
                <div class="card bg-warning btn-orange-shadow">
                    <div class="card-header">
                        <h4 class="card-title text-dark">ไอดีเข้าเล่นเกมส์</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-text text-white">
                            <p class="text-dark"><b> <i class="fa fa-user"></i> : {{Auth::guard('customer')->user()->username}}</b> 
                                <button href="javascript:void(0);" class="btn btn-success btn-sm pull-right text-dark" data-clipboard-text="{{Auth::guard('customer')->user()->username}}" onclick="alert('{{Auth::guard('customer')->user()->username}}')"> 
                                    <i class="fa fa-copy"></i> คัดลอก </button></p>
                            <p class="text-dark"><b> <i class="fa fa-key"></i> : {{Auth::guard('customer')->user()->password_generate}}</b> 
                                <button href="javascript:void(0);" class="btn btn-success btn-sm pull-right text-dark" data-clipboard-text="{{Auth::guard('customer')->user()->password_generate}}" onclick="alert('{{Auth::guard('customer')->user()->password_generate}}')"> 
                                    <i class="fa fa-copy"></i> คัดลอก </button></p>
                        </div>
                        <div class="row ">
                            <div class="col-lg-12">
                                @if($brand->game_id == 1)
                                    <a href="https://99a.bacc1688.com/" target="_blank" class="btn btn-info btn-block text-dark"> <i class="fa fa-gamepad"></i> เข้าเล่นเกมส์</a>
                                @elseif($brand->game_id == 5)
                                    <a href="https://fastbet98.com/#!/redirect?username={{Auth::guard('customer')->user()->username}}&password={{Auth::guard('customer')->user()->password_generate}}&url=LANDING_PAGE&hash={{$brand->hash}}" target="_blank" class="btn btn-info btn-block text-dark"> <i class="fa fa-gamepad"></i> เข้าเล่นเกมส์</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Page layout -->
            </div> 
            <div class="d-lg-none">
                <div class="row ml-0 mr-0">
                    <div class="col-3">
                        <a href="{{route($brand->game->name.'.member.deposit',$brand->subdomain)}}"  class="btn btn-icon btn-outline-success waves-effect img-center">
                            <i class="fa fa-hand-holding-usd"></i>
                            
                        </a>
                        <p class="text-center">เติมเงิน</p>
                    </div>
                    <div class="col-3">
                        <a href="{{route($brand->game->name.'.member.withdraw',$brand->subdomain)}}"  class="btn btn-icon btn-outline-warning waves-effect img-center">
                            <i class="fa fa-credit-card"></i>
                            
                        </a>
                        <p class="text-center">ถอนเงิน</p>
                    </div>
                    <div class="col-3">
                        <a href="{{route($brand->game->name.'.member.history',$brand->subdomain)}}" class="btn btn-icon btn-outline-info waves-effect img-center">
                            <i class="fa fa-list"></i>
                            
                        </a>
                        <p class="text-center">ประวัติ</p>
                    </div>
                    <div class="col-3">
                        <a href="{{route($brand->game->name.'.member.promotion', $brand->subdomain)}}" class="btn btn-icon btn-outline-danger waves-effect img-center">
                            <i class="fa fa-gift"></i>
                            
                        </a>
                        <p class="text-center">โปรโมชั่น</p>
                    </div>
                    <div class="col-3">
                        {{-- <a href="{{route($brand->game->name.'.member.invite',$brand->subdomain)}}" class="btn btn-icon btn-outline-info waves-effect img-center">
                            <i class="fa fa-users"></i>
                            
                        </a> --}}
                        <a href="#" class="btn btn-icon btn-outline-info waves-effect img-center">
                            <i class="fa fa-users"></i>
                            
                        </a>
                        <p class="text-center">ชวนเพื่อน</p>
                    </div>
                    <div class="col-3">
                        <a href="{{route($brand->game->name.'.member.profile',$brand->subdomain)}}" class="btn btn-icon btn-outline-danger waves-effect img-center">
                            <i class="fa fa-user"></i>
                            
                        </a>
                        <p class="text-center">โปรไฟล์</p>
                    </div>
                    <div class="col-3">
                        @php
                            $line_at = '@'.$brand->line_id;
                        @endphp
                        <a href="https://line.me/R/ti/p/~{{$line_at}}" class="btn btn-icon btn-outline-success waves-effect img-center">
                            <i class="fab fa-line"></i>
                            
                        </a>
                        <p class="text-center">แจ้งปัญหา</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-congratulations">
                    <div class="card-body text-center" onclick="alert('เร็วๆนี้ นะคะ')">
                        <img src="{{asset('frontend3/app-assets/images/elements/decore-left.png')}}" class="congratulations-img-left" alt="card-img-left" />
                        <img src="{{asset('frontend3/app-assets/images/elements/decore-right.png')}}" class="congratulations-img-right" alt="card-img-right" />
                        <div class="avatar avatar-xl bg-primary shadow">
                            <div class="avatar-content">
                                <i data-feather="users"></i>
                            </div>
                        </div>
                        <div class="text-center">
                            <h1 class="mb-1 text-white">ลิงค์ชวนเพื่อนของคุณ</h1>
                            <p class="card-text m-auto w-75"></p>
                        </div>
                    </div>
                </div>                            
            </div>
        </div>
        @if($promotions->count() > 0)
        <div class="d-xs-none">
            <h2>โปรโมชั่น</h2>
            <hr>
            <div class="row">
                @foreach($promotions as $promotion)
                    <div class="col-lg-3">
                        <img src="{{asset($promotion->img_url)}}" class="img-fluid img-center" alt="">
                        <h6 class="mb-1 mt-2">{{$promotion->name}}</h6>
                        <p class="text-secondary">ขั้นต่ำ {{$promotion->min}}</p>
                        <button class="btn btn-primary pull-right" onclick="changePromotion({{Auth::guard('customer')->user()->id}}, {{$promotion->id}}, '{{$promotion->name}}')">
                            <i class="fa fa-hand-pointer"></i>&nbsp;
                            เลือกโปรโมชั่นนี้
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        <!--/ Kick start -->
    </div>
</div>

@if(Auth::guard('customer')->user()->line_user_id === null)
    <!-- Modal-->
    <div class="modal fade mt-5" id="lineNotifyModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">กรุณากดที่กระดิ่ง เพื่อรับการแจ้งเตือน LINE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="{{route('fastbet.member.connect-line', $brand->subdomain)}}">
                        <img src="{{asset('images/line-notify.png')}}" class="img-center img-fluid" alt="">
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
            // $('#lineNotifyModal').modal('show');
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
                $.post('{{route('fastbet.update-promotion')}}', {customer_id: customer_id,promotion_id: promotion_id}, function(r) {
                    $('#alertPromotion').fadeIn();
                    if(r.status == true) {
                        // $.notify(promotion_name,'info');
                        toastr['success'](promotion_name, 'สำเร็จ', {
                            closeButton: true,
                            tapToDismiss: false,
                            timeOut: 3000,
                        });
                        setTimeout(function() {
                            $('#alertPromotion').fadeOut();
                        },3000);
                    }
                });
            }

        }

        function checkCredit() {

            $('#aCheckCredit').attr('disabled', true);

            

        }

    </script>
    
@endsection