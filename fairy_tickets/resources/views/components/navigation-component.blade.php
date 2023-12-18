<nav>
    <a href="{{ route('home.index') }}">
        <img src="{{ asset('logo/logoFairyTickets_fondoOscuro.png') }}" alt="Logo del sitio web" />
    </a>
    <x-search-component />
    <ul>
        <li><a href="{{ route('home.index') }}">Home</a></li>
    </ul>
    <button><i class="fa-solid fa-user"></i></button>
    <button><i class="fa-solid fa-bars"></i></button>
</nav>
