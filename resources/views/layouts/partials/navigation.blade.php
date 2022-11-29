<nav class="navbar navbar-expand-md navbar-dark bg-dark navigation">
    <a class="navbar-brand" href="/">Polyversal Arsenal</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav mr-auto">
            @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        Tiles
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{route('tiles.index')}}">
                            <i class="fas fa-fw fa-th-list"></i> Manage
                        </a>
                        <a class="dropdown-item" href="{{route('tiles.create')}}">
                            <i class="fas fa-fw fa-plus"></i> Create
                        </a>
                    </div>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        Tile Sheets
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{route('tile-sheets.create')}}">
                            <i class="fas fa-fw fa-plus"></i> Create
                        </a>
                    </div>
            @endauth
        </ul>
        <ul class="navbar-nav ml-auto">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                <li class="nav-item">
                    @if (Route::has('register'))
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endif
                </li>
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{csrf_field()}}
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>
