<nav>
    <a href="{{ route('home.index') }}">
        <img src="{{ asset('logo/logoFairyTickets_fondoOscuro.png') }}" alt="Logo del sitio web" />
    </a>
    <button class="icon-button"></button>
    <x-search-component />
    <ul>
        <li><a href="{{ route('home.index') }}">Home</a></li>
    </ul>
    <button class="icon-button"><i class="fa-solid fa-user"></i></button>
    <button class="icon-button"><i class="fa-solid fa-bars"></i></button>
</nav>
