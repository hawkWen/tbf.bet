
<div class="container">
    <div class="row">
        <div class="col-6 mr-auto text-left">
            <a href="#" class="pull-left">
                <i class="fa fa-user"></i>
                {{Auth::guard('customer')->user()->username}}
            </a>
            <form id="logout-form" action="{{ route('ufabet.member.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        <div class="col-6 ml-auto text-right">
            <a href="{{ route('ufabet.member.logout') }}" class="pull-right"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out-alt"></i>
                ออกจากระบบ
            </a>
            <form id="logout-form" action="{{ route('ufabet.member.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>