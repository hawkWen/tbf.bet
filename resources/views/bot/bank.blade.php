@extends('layouts.bot')

@section('css')


@endsection

@section('content')
    <input type="hidden" name="brand_id" value="{{ $brand->id }}" />
    <div class="container">
        @if (env('APP_ENV') == 'production')
            <img src="{{ $brand->logo_url }}" width="300" alt="" class="img-center"> &nbsp;
        @else
            <img src="{{ asset($brand->logo_url) }}" width="300" alt="" class="img-center"> &nbsp;
        @endif
        <h2 class="text-center">{{ $brand->name }}</h2>
    </div>

@endsection

@section('javascript')

    <script>
        $(function() {

            var i = 60;

            bankStore();

            setInterval(() => {
                bankStore();
                if (i == 0) {
                    i = 60;
                }
                i--
            }, 60000);

        });

        function bankStore() {

            var brand_id = $('#brand_id').val();

            $.post('/bank/store', {
                brand_id: brand_id
            }, function() {
                // location.reload();
            });

        }
    </script>

@endsection
