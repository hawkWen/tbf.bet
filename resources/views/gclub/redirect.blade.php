@extends('layouts.frontend3-auth')

@section('css')
    
@endsection

@section('content')

<a href="" href="{{ route($brand->game->name.'.member.logout', $brand->subdomain) }}" 
    onclick="event.preventDefault();document.getElementById('logout-form').submit();">ออกจากระบบ</a>
<h2 class="text-center">เพื่อไปยังหน้าล็อคอิน</h2>
<form id="logout-form" action="{{ route($brand->game->name.'.member.logout', $brand->subdomain) }}" method="POST" style="display: none;">
    @csrf
</form>

@section('javascript')

    <script>

        // $(function() {
        //     $('#logout-form').submit();
        // });

    </script>
    
@endsection