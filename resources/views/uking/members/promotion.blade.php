@extends('layouts.uking-auth')

@section('css')
    
@endsection

@section('content')

<div class="container">
    <div class="row">
        @foreach($promotions as $promotion)
            <div class="col-lg-12">
                <img src="{{asset($promotion->img_url)}}" alt="" class="img-fluid img-center">
                <p class="text-white text-center"><b>{{$promotion->name}}</b></p>
            </div>
        @endforeach
    </div>
</div>
    
@endsection

@section('javascript')
    
@endsection         