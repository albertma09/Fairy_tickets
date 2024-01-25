<form id="nav-search" action="{{ route('search.index') }}" method="post">
    @csrf
    {{-- <label for="search-input" class="button-filter"><i class="fa-solid fa-magnifying-glass"></i></label> --}}
    <input type="text" id="search-input" name="search-input" />
    <button type="submit" class="button button-search"><i class="fa-solid fa-magnifying-glass"></i></button>
</form>
