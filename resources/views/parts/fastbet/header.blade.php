
<div class="container">
    <div class="row">
        <div class="col-6 mr-auto text-left text-white text-shadow">
            <a href="#" class="pull-left" data-clipboard-text="{{Auth::guard('customer')->user()->username}}" onclick="alert('คัดลอกไปยังคลิปบอร์ดแล้ว {{Auth::guard('customer')->user()->username}}')">

            @if(Auth::guard('customer')->user()->img_url == '')
            <i class="fa fa-users"></i>
        @else
            <img src="{{Auth::guard('customer')->user()->img_url}}" class="img-fluid rounded d-inline" width="25" alt="">
        @endif
                {{Auth::guard('customer')->user()->username}}
            </a>
        </div>
        <div class="col-6 ml-auto text-right text-white text-shadow">
            <a href="{{ route('fastbet.member.logout', $brand->subdomain) }}" 
                onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="pull-right">
                <i class="fa fa-sign-out-alt"></i>
            </a>
            <form id="logout-form" action="{{ route('fastbet.member.logout', $brand->subdomain) }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>