<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')
<body>
    <main>
        <div id="homepicture" class="container-fluid">
            @include('layouts.partials.publicnavbar')
            @yield('searchbox')
            @yield('login-content')
            @yield('reset_box')
            @yield('reset_confirm_box')
            @yield('page_404')
        </div>
        @yield('content')
    </main>
    <footer>
        @include('layouts.partials.footer')
    </footer>
</body>
</html>
