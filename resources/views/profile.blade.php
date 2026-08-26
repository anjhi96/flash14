<x-app-layout>
    <x-slot name="header">
        <x-page-header eyebrow="Pengaturan Pengguna" icon="person" :title="__('Profile & Keamanan')" />
    </x-slot>

    <div class="py-8 bg-surface min-h-[calc(100vh-140px)] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-surface-container-lowest border border-outline-variant rounded-xl">
                <div class="max-w-xl text-on-surface">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-surface-container-lowest border border-outline-variant rounded-xl">
                <div class="max-w-xl text-on-surface">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-surface-container-lowest border border-outline-variant rounded-xl">
                <div class="max-w-xl text-on-surface">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
