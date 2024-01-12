<nav>
    <div class="nav-main">
        <a href="{{ route('home.index') }}" class="button">
            <img src="{{ asset('logo/logoFairyTickets_fondoOscuro.png') }}" alt="Logo del sitio web" />
        </a>
        <x-search-component />
        <ul id="nav-dropdown-menu">
            <li><a href="{{ route('home.index') }}" class="{{ Request::is('home*') ? 'nav-active' : '' }}">Home</a></li>
            <li><a href="#">Prueba</a></li>
        </ul>
        @if (auth()->check())
        @auth
        <!-- Si el usuario está autenticado, muestra un enlace para cerrar sesión con una alerta de confirmación -->
        <a href="#" id="logout-link">
            Cerrar Sesión
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endauth
        @else
            <a href="{{ route('login') }}" class="button button-icon "><i class="fa-solid fa-user"></i></a>
        @endif
        <button class="button  button-icon" id="menu-toggle"><i class="fa-solid fa-bars"></i></button>
    </div>
    <x-category-filter-component />
</nav>
