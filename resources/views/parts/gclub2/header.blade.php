<div class="container-fluid h-100 loader-display">
    <div class="row h-100">
        <div class="align-self-center col">
            <div class="logo-loading">
                <div class="icon icon-100 mb-4 rounded-circle">
                    <img src="{{ $brand->logo_url }}" alt="" class="w-100">
                </div>
                <h4 class="text-default">กำลังโหลดหน้า</h4>
                <p class="text-secondary">Powered by
                    <a href="https://casinoauto.io"> {{ env('APP_NAME') }}/a>
                </p>
                <div class="loader-ellipsis">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="backdrop"></div>
