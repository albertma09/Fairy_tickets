<nav>
    <div class="nav-main">
        <a href="{{ route('home.index') }}">
            <img src="{{ asset('logo/logoFairyTickets_fondoOscuro.png') }}" alt="Logo del sitio web" />
        </a>
        <x-search-component />
        <ul id="nav-dropdown-menu">
            <li><a href="{{ route('home.index') }}" class="{{ Request::is('home*') ? 'nav-active' : '' }}">Home</a></li>
            <li><a href="#">Prueba</a></li>
        </ul>
        @if (auth()->check())
            <p>{{ auth()->user()->name }}</p>
        @else
            <a href="{{ route('login') }}" class="icon-button"><i class="fa-solid fa-user"></i></a>
        @endif
        <button class="icon-button" id="menu-toggle"><i class="fa-solid fa-bars"></i></button>
    </div>
    <x-category-filter-component />
</nav>
