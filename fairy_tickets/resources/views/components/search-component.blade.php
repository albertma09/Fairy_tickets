<form id="nav-search" action="{{ route('home.index') }}" method="post">
    @csrf
    {{-- <label for="search-input" class="icon-button"><i class="fa-solid fa-magnifying-glass"></i></label> --}}
    <input type="text" id="search-input" name="search-input" />
    <button type="submit" class="icon-button"><i class="fa-solid fa-magnifying-glass"></i></button>
</form>