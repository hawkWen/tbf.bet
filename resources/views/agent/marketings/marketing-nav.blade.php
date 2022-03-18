<!--begin::Card-->
<div class="card card-custom card-shadowless">
    <!--begin::Header-->
    <div class="card-header">
        {{-- <div class="card-title"> --}}
        <h2 class=" text-center">
            <i class="fas fa-balance-scale mr-1"></i>
            การตลาด
        </h2>
        {{-- </div> --}}
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link @if (Route::currentRouteName() == 'agent.marketing.top') active @endif"
                    href="{{ route('agent.marketing.top') }}">ภาพรวม
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if (Route::currentRouteName() == 'agent.marketing.customer') active @endif"
                    href="{{ route('agent.marketing.customer') }}">ลูกค้า</a>
            </li>
        </ul>
    </div>
</div>
<!--end::Card-->
