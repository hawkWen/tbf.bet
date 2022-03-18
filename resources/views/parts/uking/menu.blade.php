<div class="footer">
    <div class="row no-gutters justify-content-center">
        <div class="col-auto">
            <a href="{{route('uking.member.deposit',$brand->subdomain)}}" @if(Request::is($brand->subdomain.'/member/deposit')) class="active" @endif>
                <i class="fa fa-hand-holding-usd"></i>
                <p>เติมเงิน</p>
            </a>
        </div>
        <div class="col-auto">
            <a href="{{route('uking.member.withdraw',$brand->subdomain)}}" @if(Request::is($brand->subdomain.'/member/withdraw')) class="active" @endif>
                <i class="fa fa-credit-card"></i>
                <p>ถอนเงิน</p>
            </a>
        </div>
        <div class="col-auto">
            <a href="{{route('uking.member',$brand->subdomain)}}"  @if(Request::is($brand->subdomain.'/member')) class="active" @endif>
                <i class="fa fa-home"></i>
                <p>หน้าแรก</p>
            </a>
        </div>
        <div class="col-auto">
            <a href="{{Auth::guard('customer')->user()->line_menu_member}}">
            {{-- <a href="https://ukingbet.com/#!/redirect?username={{Auth::guard('customer')->user()->username}}&password={{Auth::guard('customer')->user()->password_generate}}&url=LANDING_PAGE&hash={{$brand->hash}}" target="_blank" class=""> --}}
                <i class="fa fa-gamepad"></i>
                <p>เข้าเกมส์</p>
            </a>
        </div>
        <div class="col-auto">
            @php
                $brand = '@'.$brand->line_id;
            @endphp
            <a href="https://line.me/R/ti/p/~{{$brand}}" target="_blank" class="">
                <i class="fab fa-line text-success"></i>
                <p>ติดต่อเรา</p>
            </a>
        </div>
    </div>
</div>