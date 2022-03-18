
<header class="header">
    <div class="row">
        <div class="text-left col align-self-center">
            <a class="navbar-brand" href="#">
                <img src="{{$brand->logo_url}}" alt="" width="25">
                {{$brand->name}}
            </a>
        </div>
        <div class="ml-auto pl-0">
            <a href="#" class="btn btn-40 btn-link">
                <span class="material-icons">account_circle</span>
                <!-- <figure class="m-0 background">
                    <img src="img/user1.png" alt="">
                </figure> -->
            </a>
        </div>
        <div class="ml-auto pl-0">
            <a href="{{ route('gclub.member.logout', $brand->subdomain) }}" 
                onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-40 btn-link">
                <i class="fa fa-sign-out-alt"></i>
                <!-- <figure class="m-0 background">
                    <img src="img/user1.png" alt="">
                </figure> -->
            </a>
            <form id="logout-form" action="{{ route('gclub.member.logout', $brand->subdomain) }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</header>

@if(!Request::is($brand->subdomain.'/member/invite'))
<!-- page content start -->
<div class="container mt-3 mb-4 text-center">
    <!-- <p class="text-default-secondary">คลิกที่โลโก้เพื่อเข้าเกมส์ </p>
    <div class="avatar avatar-120 rounded mb-3">
        <div class="background">
            <img src="https://fastbet.casinoauto.io/storage/games/dIwCf09gPXyT1aRIxwwaoDE1NIj3feoHGaPqYoyV.png" alt="" class="w-100">
        </div>
    </div> -->

    <h2 class="text-white" style="padding: 50px 0px;">{{Auth::guard('customer')->user()->credit}} เครดิต</h2>
    <p class="text-white mb-4">อัพเดทเครดิตล่าสุด {{Auth::guard('customer')->user()->last_update_credit}}</p>
</div>
@endif