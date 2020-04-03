<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')
<body>
    <div id="app">
        @include('layouts.partials.uprnavbar')
        <main class="py-4">
            @yield('content')
        </main>
        <footer>
            @include('layouts.partials.footer')
        </footer>
    </div>
</body>
</html>
