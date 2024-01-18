@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')
    <h2>Próximos eventos</h2>

    {{-- @foreach ($categories as $category)
        @foreach ($events as $event)
            <x-categorizable-card-component :category="$category" />
        @endforeach
    @endforeach --}}


    @foreach ($categories as $category)
        <div class="category-card">
            <div class="category-card-data">
                <div class="category-card-data-name">{{ ucfirst($category->name) }}</div>
                <div class="category-card-data-total">
                    <div class="category-card-data-total-label">Total eventos</div>
                    <div class="category-card-data-total-number">{{ $category->total }}
                    </div>
                    <div class="category-card-events">
                        @forelse ($events as $event)
                            @if ($event->category === $category->name)
                                <x-events-component :event="$event" />
                            @endif
                        @empty
                            <div>No hay eventos</div>
                        @endforelse
                    </div>
                    <div class="category-card-more">
                        <a class="button button-brand">Ver más eventos</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach




@endsection
