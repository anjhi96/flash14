@props(['eyebrow' => null, 'icon' => null, 'title', 'description' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-4 md:flex-row md:items-center md:justify-between']) }}>
    <div class="space-y-1.5 min-w-0">
        @if ($eyebrow)
            <x-badge variant="primary" class="uppercase">
                @if ($icon)
                    <span class="material-symbols-outlined text-[13px]">{{ $icon }}</span>
                @endif
                {{ $eyebrow }}
            </x-badge>
        @endif
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-on-surface">{{ $title }}</h1>
        @if ($description)
            <p class="text-sm text-on-surface-variant max-w-2xl">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex items-center gap-3 shrink-0">
            {{ $actions }}
        </div>
    @endisset
</div>
