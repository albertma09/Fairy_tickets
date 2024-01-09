<nav>
    <a href="{{ route('home.index') }}">
        <img src="{{ asset('logo/logoFairyTickets_fondoOscuro.png') }}" alt="Logo del sitio web" />
    </a>
    <x-search-component />
    <ul id="nav-dropdown-menu">
        <li><a href="{{ route('home.index') }}" class="{{ Request::is('home*') ? 'nav-active' : '' }}">Home</a></li>
        <li><a href="#" class="{{ Request::is('home*') ? 'nav-active' : '' }}">Prueba</a></li>
    </ul>
    <button class="icon-button"><i class="fa-solid fa-user"></i></button>
    <button class="icon-button" id="menu-toggle"><i class="fa-solid fa-bars"></i></button>
</nav>
