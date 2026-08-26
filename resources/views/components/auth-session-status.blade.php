@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-state-success']) }}>
        {{ $status }}
    </div>
@endif
