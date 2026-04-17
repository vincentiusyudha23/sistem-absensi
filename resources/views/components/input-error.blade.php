@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'alert alert-danger py-2 small mb-2']) }}>
        @foreach ((array) $messages as $message)
            <div>{{ $message }}</div>
        @endforeach
    </div>
@endif