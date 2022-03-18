
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto img-center">
                <a class="navbar-brand" href="{{route('gclub.member',$brand->subdomain)}}">
                    <img src="{{$brand->logo_url}}" width="150" class="img-fluid img-center" alt="">
                    
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="@if(Request::is($brand->subdomain.'/member')) active @endif nav-item">
                <a href="{{route($brand->game->name.'.member', $brand->subdomain)}}" class="d-flex align-items-center" ><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Home">หน้าแรก</span></a>
            </li>
            <li class="@if(Request::is($brand->subdomain.'/member/deposit')) active @endif nav-item">
                <a href="{{route($brand->game->name.'.member.deposit', $brand->subdomain)}}" class="d-flex align-items-center" ><i data-feather="dollar-sign"></i><span class="menu-title text-truncate" data-i18n="Home">เติมเงิน</span></a>
            </li>
            <li class="@if(Request::is($brand->subdomain.'/member/withdraw')) active @endif nav-item">
                <a href="{{route($brand->game->name.'.member.withdraw', $brand->subdomain)}}"  class="d-flex align-items-center" ><i data-feather="credit-card"></i><span class="menu-title text-truncate" data-i18n="Home">ถอนเงิน</span></a>
            </li>
            <li class="nav-item">
                <a href="{{route($brand->game->name.'.member.history', $brand->subdomain)}}"  class="d-flex align-items-center" ><i data-feather="list"></i><span class="menu-title text-truncate" data-i18n="Home">ประวัติการทำธุรกรรม</span></a>
            </li>
            <li class="nav-item">
                <a href="{{route($brand->game->name.'.member.promotion', $brand->subdomain)}}" class="d-flex align-items-center" ><i data-feather="gift"></i><span class="menu-title text-truncate" data-i18n="Home">โปรโมชั่น</span></a>
            </li>
            <li class="nav-item">
                {{-- <a href="{{route($brand->game->name.'.member.invite', $brand->subdomain)}}"  class="d-flex align-items-center" ><i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Home">ลิงค์ชวนเพื่อน</span></a> --}}
                <a href="#" onclick="alert('เร็วๆนี้ นะคะ')" class="d-flex align-items-center" ><i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Home">ลิงค์ชวนเพื่อน</span></a>
            </li>
            <li class="nav-item">
                <a href="{{route($brand->game->name.'.member.profile', $brand->subdomain)}}"  class="d-flex align-items-center" ><i data-feather="user"></i><span class="menu-title text-truncate" data-i18n="Home">โปรไฟล์ของฉัน</span></a>
            </li>
            <li class="nav-item">
                <a href="{{ route($brand->game->name.'.member.logout', $brand->subdomain) }}" 
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="d-flex align-items-center" ><i data-feather="log-out"></i><span class="menu-title text-truncate" data-i18n="Home">ออกจากระบบ</span></a>
            </li>
        </ul>
    </div>
</div>
{{-- <div class="pull-right d-xs-none">
    @if($brand->game_id == 1)
        <a href="https://99a.bacc1688.com/" target="_blank" class="btn btn-orange-shadow mt-2 mr-1"> <i class="fa fa-gamepad"></i> เข้าเล่นเกมส์</a>
    @elseif($brand->game_id == 5)
        <a href="https://fastbet98.com/#!/redirect?username={{Auth::guard('customer')->user()->username}}&password={{Auth::guard('customer')->user()->password_generate}}&url=LANDING_PAGE&hash={{$brand->hash}}" target="_blank" class="btn btn-orange-shadow mt-2 mr-1"> <i class="fa fa-gamepad"></i> เข้าเล่นเกมส์</a>
    @endif
</div> --}}
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>

<div class="pull-right p-2 d-lg-none">
    <img src="{{$brand->logo_url}}" class="img-fluid rounded-circle" width="50" alt="">
</div>