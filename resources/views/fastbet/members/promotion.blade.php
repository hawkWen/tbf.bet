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
        @if($promotions->count() > 0)
            <div class="">  
                <h2>โปรโมชั่น</h2>
                <hr>
                <div class="row">
                    @foreach($promotions as $promotion)
                        <div class="col-lg-3 col-6">
                            <img src="{{asset($promotion->img_url)}}" class="img-fluid img-center" alt="">
                            <h6 class="mb-1 mt-2">{{$promotion->name}}</h6>
                            <p class="text-secondary">ขั้นต่ำ {{$promotion->min}}</p>
                            @if($promotion->type_promotion == 1 || $promotion->type_promotion == 2  || $promotion->type_promotion == 3)
                                <button class="btn btn-primary pull-right" onclick="changePromotion({{Auth::guard('customer')->user()->id}}, {{$promotion->id}}, '{{$promotion->name}}')">
                                    <i class="fa fa-hand-pointer"></i>&nbsp;
                                    เลือกโปรโมชั่นนี้
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>


@endsection

@section('javascript')

<script>



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

</script>
    
@endsection         