
<div class="bottombar-mobile d-lg-none">
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="{{route($brand->game->name.'.member',$brand->subdomain)}}" class="nav-link text-info" href="index.html"> <i class="fa fa-home"></i> <span>หน้าแรก </span></a>
        </li>
        <li class="nav-item">
            <a href="{{route($brand->game->name.'.member.deposit',$brand->subdomain)}}" class="nav-link text-success" href="deposit.html"> <i class="fa fa-hand-holding-usd"></i><span>เติมเงิน</span></a>
        </li>
        <li class="nav-item middle-item">
            @if($brand->game_id == 1)
                <a href="https://99a.bacc1688.com/" class="nav-link"> 
                    <i class="fa fa-gamepad"></i>
                    <!-- <img src="images/icon-dice.png" width="50" alt="">     -->
                </a><span>เข้าเล่นเกม</span>
            @elseif($brand->game_id == 5)
                <a href="https://fastbet98.com/#!/redirect?username={{Auth::guard('customer')->user()->username}}&password={{Auth::guard('customer')->user()->password_generate}}&url=LANDING_PAGE&hash={{$brand->hash}}" class="nav-link"> 
                    <i class="fa fa-gamepad"></i>
                    <!-- <img src="images/icon-dice.png" width="50" alt="">     -->x
                </a><span>เข้าเล่นเกม</span>
            @endif
        </li>
        <li class="nav-item">
            <a href="{{route($brand->game->name.'.member.withdraw',$brand->subdomain)}}" class="nav-link text-warning" href="withdraw.php">  <i class="fa fa-credit-card"></i><span>ถอนเงิน</span></a>
        </li>
        <li class="nav-item">
            <a href="{{route($brand->game->name.'.member.profile',$brand->subdomain)}}" class="nav-link text-danger" href="profile.php"> <i class="fa fa-user"></i> <span>โปรไฟล์ฉัน</span></a>
        </li>
    </ul>
</div>