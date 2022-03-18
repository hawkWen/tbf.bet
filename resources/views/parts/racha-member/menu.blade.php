<div class="btn-group btn-footer" role="group" aria-label="Basic example">
    <a href="{{route('racha.member.deposit',$brand->subdomain)}}" class="btn btn-secondary">
        <i class="fa fa-hand-holding-usd"></i>
        <span class="d-block mt-2">เติมเงิน</span>
    </a>
    <a href="{{route('racha.member.withdraw',$brand->subdomain)}}" class="btn btn-secondary">
        <i class="fa fa-credit-card"></i>
        <span class="d-block mt-2">ถอนเงิน</span>
    </a>
    <a href="{{route('racha.member',$brand->subdomain)}}" class="btn btn-secondary btn-home">
        <i class="fa fa-home"></i>  
        <span class="d-block mt-2">หน้าหลัก</span>
    </a>
    <a id="btnLogin" href="#" class="btn btn-secondary">
        <i class="fa fa-gamepad"></i>
        <span class="d-block mt-2">เข้าเกมส์</span>
    </a>
    @php
        $brand = '@'.$brand->line_id;
    @endphp
    <a href="https://line.me/R/ti/p/~{{$brand}}" class="btn btn-secondary" target="_blank">
        <img src="{{asset('images/line.png')}}" class="img-fluid img-center" width="20" alt="">
        <span class="d-block mt-2">ติดต่อเรา</span>
    </a>
</div>