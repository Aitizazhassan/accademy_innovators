@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-danger alert-dismissible' , 'role' => 'alert']) }}>
        <p class="mb-0">{{ $status }}</p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif