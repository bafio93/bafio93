<nav class="navbar navbar-expand-md navbar-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            {{-- {{ config('app.name', 'Laravel') }} --}}
            <img src="/assets/images/boolbnb-logo-text.png" width="160" height="49" alt="bool-bnb-logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                {{-- Selettore della lingua --}}
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <li class="nav-item">
                            <a class="nav-link text-dark" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                <img src="{{ $properties['flag'] }}">
                            </a>
                        </li>
                    @endforeach
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item {{Route::currentRouteName() == 'login' ? 'active' : ""}}">
                        <a class="nav-link text-dark" href="{{ route('login') }}">{{ __('registration.Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item {{Route::currentRouteName() == 'login' ? 'active' : ""}}">
                            <a class="nav-link text-dark" href="{{ route('register') }}">{{ __('registration.Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item {{Route::currentRouteName() == 'upr.flats.index' ? 'active' : ""}}">
                        <a class="nav-link text-dark" href="{{ route('upr.flats.index') }}">{{ __('upr_nav.Dashboard') }}</a>
                    </li>
                    @if (Auth::user()->flats->count())
                        <li class="nav-item {{Route::currentRouteName() == 'messages.index' ? 'active' : ""}}">
                            <a class="nav-link text-dark" href="{{ route('messages.index') }}">{{ __('upr_nav.Messages') }}</a>
                        </li>
                    @endif
                    <li class="nav-item {{Route::currentRouteName() == 'upr.flats.create' ? 'active' : ""}}">
                        <a class="nav-link text-dark" href="{{ route('upr.flats.create') }}">{{ __('upr_nav.Offer_an_apartment') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" style="text-transform:capitalize" class="nav-link dropdown-toggle text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item text-dark" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{__('upr_nav.Logout')}}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
