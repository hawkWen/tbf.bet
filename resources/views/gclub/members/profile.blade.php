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
            <div class="col-lg-4">
                <div class="card card-congratulation-medal">
                    <div class="card-body">
                        <h2> <i class="fa fa-user"></i> โปรไฟล์ของฉัน</h2>
                        <p class="card-text font-small-3 mt-3">ชื่อ: {{$customer->name}}</p>
                        <p class="card-text font-small-3">เบอร์โทรศัพท์: {{$customer->telephone}}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-warning btn-orange-shadow">
                    <div class="card-header">
                        <h4 class="card-title text-dark">ไอดีเข้าเล่นเกมส์</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-text text-white">
                            <p class="text-dark"><b> <i class="fa fa-user"></i> : {{$customer->username}}</b> 
                                <button href="javascript:void(0);" class="btn btn-success btn-sm pull-right text-dark" data-clipboard-text="{{$customer->username}}" onclick="alert('{{$customer->username}}')"> 
                                    <i class="fa fa-copy"></i> คัดลอก </button></p>
                            <p class="text-dark"><b> <i class="fa fa-key"></i> : {{$customer->password_generate}}</b> 
                                <button href="javascript:void(0);" class="btn btn-success btn-sm pull-right text-dark" data-clipboard-text="{{$customer->password_generate}}" onclick="alert('{{$customer->password_generate}}')"> 
                                    <i class="fa fa-copy"></i> คัดลอก </button></p>
                        </div>
                        <div class="row ">
                            <div class="col-lg-12">
                                @if($brand->game_id == 1)
                                    <a href="https://99a.bacc1688.com/" class="btn btn-info btn-block text-dark"> <i class="fa fa-gamepad"></i> เข้าเล่นเกมส์</a>
                                @elseif($brand->game_id == 5)
                                    <a href="https://fastbet98.com/#!/redirect?username={{$customer->username}}&password={{$customer->password_generate}}&url=LANDING_PAGE&hash={{$brand->hash}}" class="btn btn-info btn-block text-dark"> <i class="fa fa-gamepad"></i> เข้าเล่นเกมส์</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-lg-4">
                <div class="card" style="background-color: {{$customer->bank->bg_color}};color: {{$customer->bank->font_color}} !important;border-radius: 5px;">
                    <div class="card-header">
                        <h4 class="card-title text-white">บัญชีธนาคารของคุณ</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-text text-white">
                            <img src="{{asset($customer->bank->logo)}}" class="img-fluid mb-1" width="50" alt="">
                            <h4><b>{{$customer->bank_account}}  </b></h4>
                            <p><b>{{$customer->name}}</b></p>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-lg-12 mb-5">
                <a href="{{ route('gclub.member.logout', $brand->subdomain) }}" 
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-danger d-flex align-items-center" >
                    <i class="fa fa-sign-out-alt"></i>&nbsp;
                    <span class="menu-title text-truncate" data-i18n="Home">ออกจากระบบ</span></a>
            </div> 
        </div>
    </div>
</div>

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
                $.post('{{route('gclub.update-promotion')}}', {customer_id: customer_id,promotion_id: promotion_id}, function(r) {
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