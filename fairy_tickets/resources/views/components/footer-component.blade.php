<div class="navigation-map">
    <h3>Navigation Map</h3>
    <ul>
        <li><a href="{{ route('home.index') }}">Hogar</a></li>
        <li><a href="#">Sobre nosotros</a></li>
        <li><a href="#">Noticias legales</a></li>
    </ul>
</div>
<div class="promoter-link">
    <h3>Link a promotor</h3>
    <a href=" {{ auth()->check() ? route('promotor', ['userId' => auth()->user()->id]) : route('login') }}">Página promotor</a>
   
</div>