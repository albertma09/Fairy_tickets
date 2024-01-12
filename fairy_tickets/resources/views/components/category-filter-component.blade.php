<div>
    <form class="filter-categories" action="{{route('searchByCategory.index')}}"  method="post">
        @csrf
        <button class="filter-button" type="submit" name="category-item" value="cine" >Cine</button>
        <button class="filter-button" type="submit" name="category-item" value="comedia" >Comedia</button>
        <button class="filter-button" type="submit" name="category-item" value="conferencia">Conferencia</button>
        <button class="filter-button" type="submit" name="category-item" value="danza">Danza</button>
        <button class="filter-button" type="submit" name="category-item" value="hogar">Hogar</button>
        <button class="filter-button" type="submit" name="category-item" value="moda">Moda</button>
        <button class="filter-button" type="submit" name="category-item" value="musica">Musica</button>
        <button class="filter-button" type="submit" name="category-item" value="opera">Opera</button>
        <button class="filter-button" type="submit" name="category-item" value="salud">Salud</button>
        <button class="filter-button" type="submit" name="category-item" value="teatro">Teatro</button>
    </form>
</div>