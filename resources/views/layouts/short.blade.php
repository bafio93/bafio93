<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')
<body>
    <main>
        <div id="short-head" class="container-fluid">
            @include('layouts.partials.publicnavbar')
            @yield('searchbox')
            @yield('login-content')
        </div>
        @yield('content')
    </main>
    <footer>
        @include('layouts.partials.footer')
    </footer>
</body>
</html>
