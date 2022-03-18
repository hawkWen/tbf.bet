@extends('layouts.fastbet89')

@section('css')
    
@endsection

@section('content')

    
        <!-- page content start -->
    <div class="container mb-4 text-center text-white">
        <div class="row">
            <div class="col col-sm-8 col-md-6 col-lg-5 mx-auto">
                <img src="{{asset('images/refer.png')}}" alt="" class="mw-100 mb-4">
                @if($promotion)
                <form action="{{route('fastbet.member.invite.store', $customer->brand->subdomain)}}" method="post" id="formInviteStore">
                    <input type="hidden" name="invite_bonus" value="{{$customer_invite_bonus->sum('invite_bonus')}}" />
                    <input type="hidden" name="promotion_id" value="{{$promotion->id}}" />
                    <input type="hidden" name="customer_id" value="{{$customer->id}}" />
                    <p>{{$promotion->name}}</p>
                    <h5>$ {{number_format($customer_invite_bonus->sum('invite_bonus'),2)}}</h5>
                    <button type="submit" data-toggle="modal" data-target="#" class="btn btn-default btn-sm mt-2 mb-2 mx-auto rounded">
                        <i class="fa fa-hand-pointer"></i>
                        รับโบนัส</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    <div class="main-container">
        <div class="container mb-4">
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto pr-0">
                            <div class="avatar avatar-50 border-0 bg-danger-light rounded-circle text-danger">
                                <i class="material-icons vm text-template">card_giftcard</i>
                            </div>
                        </div>
                        <div class="col-auto align-self-center">
                            <h6 class="mb-1">ชวนเพื่อนเพื่อรับเครดิตเพิ่มเติม</h6>
                            <p class="small text-secondary">Share your referal link and start earning</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="alert alert-success d-none" id="successmessage">ลิงค์ชวนเพื่อนของคุณ</div>
            <div class="input-group mb-3">
                <input type="text" class="form-control dark" placeholder="refferal Link" value="{{$customer->invite_url}}" id="referallink">
                <div class="input-group-append">
                    <button class="btn btn-default rounded" type="button" id="btnGroupAddon" data-clipboard-text="{{Auth::guard('customer')->user()->invite_url}}" onclick="alert('{{Auth::guard('customer')->user()->invite_url}}')">คัดลอกลิงค์</button>
                </div>
            </div>

        </div>
        <div class="container mb-4">
            <p class="pull-right">เพื่อนของคุณที่มีในตอนนี้ {{$customer_invites->count()}} คน</p>
            <h6 class="subtitle mb-3">เพื่อนของคุณ</h6>
            <div class="clearfix"></div>
            <div class="card">
                <div class="card-body px-0">
                    <ul class="list-group list-group-flush">
                        @foreach($customer_invites as $customer_invite)
                        <li class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto pr-0">
                                    <div class="avatar avatar-40 rounded">
                                        @if($customer_invite->deposits->where('status','=',1)->first())
                                            <span class="badge badge-primary">เติมเงินแล้ว</span>
                                        @else
                                            <span class="badge badge-danger">ยังไม่มีการเติมเงิน</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col align-self-center pr-0">
                                    <h6 class="font-weight-normal mb-1">{{$customer_invite->name}}</h6>
                                    <p class="small text-secondary"><b>สมัครเมื่อวันที่</b> : {{$customer_invite->created_at}}</p>
                                </div>
                                <div class="col-auto">
                                    <h6 class="text-success">
                                        @if($customer_invite->deposits->where('status','=',1)->sortByDesc('created_at')->first())
                                            {{number_format($customer_invite->deposits->where('status','=',1)->first()->amount,2)}}
                                            <span class="badge badge-success pull-right">ยอดฝากแรถ + 
                                                {{number_format($customer_invite->invite_bonus,2)}}
                                            </span>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="row pull-right">
                        <div class="col-lg-12">
                            {{$customer_invites->links("pagination::bootstrap-4")}}
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
        $('#formInviteStore').preventDoubleSubmission();
    });

</script>
    
@endsection