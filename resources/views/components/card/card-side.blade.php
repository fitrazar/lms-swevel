@props(['title', 'image'])

<div {!! $attributes->merge(['class' => 'card lg:card-side bg-base-100 shadow-xl']) !!}>
    <figure>
        <img src="{{ asset($image) }}" alt="{{ $title ?? 'alt' }}" class="md:h-72 max-h-72 w-full object-cover" />
    </figure>
    <div class="card-body">
        <h2 class="card-title">{{ $title ?? '' }}</h2>
        {{ $slot }}
    </div>
</div>
