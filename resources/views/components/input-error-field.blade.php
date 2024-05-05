@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'invalid-feedback animated fadeIn', 'style' => 'display:block;']) }}>
        @foreach ((array) $messages as $message)
        {{ $message }}
        @endforeach
    </div>
@endif
