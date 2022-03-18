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
        <hr>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="list-group list-group-flush">
                            @foreach($histories->sortByDesc('created_at') as $history)
                                <li class="list-group-item p-2">
                                    <div class="row align-items-center">
                                        <div class="col-auto pr-0">
                                            @if(isset($history->status_credit))
                                                <span class="text-danger">
                                                    <i class="fa fa-credit-card"></i>
                                                </span>
                                            @else
                                                <span class="text-success">
                                                    <i class="fa fa-hand-holding-usd"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col align-self-center pr-0">
                                            <h6 class="small text-secondary cut-text">
                                                @if($history->promotion)
                                                    {{$history->promotion->name}}
                                                @else
                                                    @if(isset($history->status_credit))
                                                        ถอนเงิน
                                                    @else
                                                        ไม่รับโบนัส
                                                    @endif
                                                @endif
                                            </h6>
                                            <p class="small text-secondary">
                                                {{$history->created_at->format('d/m/y H:i:s')}}
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            @if(isset($history->status_credit))
                                                <h6 class="text-danger">
                                                    {{$history->amount}}
                                                </h6>
                                            @else
                                                <h6 class="text-success">
                                                    {{$history->amount}}
                                                    @if($history->promotion)
                                                        + {{$history->bonus}}
                                                    @endif
                                                </h6>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@endsection

@section('javascript')
    
@endsection