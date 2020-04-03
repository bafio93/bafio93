<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')
<body>
    <main>
        <div id="error-picture" class="container-fluid">
            @include('layouts.partials.publicnavbar')
            @yield('page_404')
        </div>
        @yield('content')
    </main>
    <footer>
        @include('layouts.partials.footer')
    </footer>
</body>
</html>
