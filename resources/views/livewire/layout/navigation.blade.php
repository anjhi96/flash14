<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-surface-container-lowest/95 backdrop-blur-md border-b border-outline-variant text-on-surface sticky top-0 z-50 transition-colors duration-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" wire:navigate class="flex items-center space-x-2.5 group">
                        <img src="{{ asset('flash.png') }}" alt="FlashDev Logo" class="h-8 w-auto transition-transform duration-200 group-hover:scale-105">
                        <span class="text-lg font-bold text-on-surface tracking-tight">FLASH<span class="text-primary">DEV</span></span>
                    </a>
                </div>

                <!-- Navigation Links (M3 Segmented / Nav Tabs) -->
                <div class="hidden space-x-1 sm:ms-8 sm:flex items-center">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                        {{ __('Beranda') }}
                    </x-nav-link>
                    <x-nav-link :href="route('services')" :active="request()->routeIs('services')" wire:navigate>
                        {{ __('Layanan') }}
                    </x-nav-link>
                    <x-nav-link :href="route('portfolio')" :active="request()->routeIs('portfolio')" wire:navigate>
                        {{ __('Portfolio') }}
                    </x-nav-link>
                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')" wire:navigate>
                        {{ __('Tentang') }}
                    </x-nav-link>
                    <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')" wire:navigate>
                        {{ __('Kontak') }}
                    </x-nav-link>
                    @auth
                        @if (auth()->user()?->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate>
                                <span class="material-symbols-outlined text-[18px] me-1.5">admin_panel_settings</span>
                                {{ __('Admin Panel') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                                <span class="material-symbols-outlined text-[18px] me-1.5">dashboard</span>
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Topbar Items (Theme Switcher & Settings) -->
            <div class="flex items-center space-x-2">
                <!-- Theme Toggle Button -->
                <div x-data="{
                    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                    toggle() {
                        this.darkMode = !this.darkMode;
                        if (this.darkMode) {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('theme', 'dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('theme', 'light');
                        }
                    }
                }">
                    <button @click="toggle()" type="button" class="w-9 h-9 flex items-center justify-center rounded-lg border border-outline-variant bg-surface-container text-on-surface-variant hover:text-primary hover:border-primary/40 transition-colors cursor-pointer" title="Toggle Theme">
                        <span class="sr-only">Toggle theme</span>
                        <span class="material-symbols-outlined text-[20px]" x-text="darkMode ? 'light_mode' : 'dark_mode'"></span>
                    </button>
                </div>

                <!-- Settings Dropdown / Auth Links -->
                <div class="hidden sm:flex sm:items-center">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-outline-variant text-sm font-semibold rounded-lg text-on-surface-variant bg-surface-container hover:text-on-surface hover:border-outline focus:outline-none transition ease-in-out duration-150 cursor-pointer">
                                    <span class="material-symbols-outlined text-[18px] text-primary">account_circle</span>
                                    <div x-data="{{ json_encode(['name' => auth()->user()->name ?? '']) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">expand_more</span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile')" wire:navigate>
                                    <span class="inline-flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">person</span>
                                        {{ __('Profile') }}
                                    </span>
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <button wire:click="logout" class="w-full text-start">
                                    <x-dropdown-link>
                                        <span class="inline-flex items-center gap-2 text-state-error">
                                            <span class="material-symbols-outlined text-[18px]">logout</span>
                                            {{ __('Log Out') }}
                                        </span>
                                    </x-dropdown-link>
                                </button>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="px-3.5 py-1.5 text-sm font-semibold rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                            Masuk
                        </a>
                    @endauth
                </div>

                <!-- Hamburger Mobile Toggle -->
                <div class="flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container focus:outline-none transition duration-150 cursor-pointer">
                        <span class="material-symbols-outlined text-[24px]" x-text="open ? 'close' : 'menu'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Drawer -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-outline-variant bg-surface-container-lowest px-4 py-3">
        <div class="space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" wire:navigate>
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('services')" :active="request()->routeIs('services')" wire:navigate>
                {{ __('Layanan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('portfolio')" :active="request()->routeIs('portfolio')" wire:navigate>
                {{ __('Portfolio') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')" wire:navigate>
                {{ __('Tentang') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')" wire:navigate>
                {{ __('Kontak') }}
            </x-responsive-nav-link>
            @auth
                @if (auth()->user()?->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('Admin Panel') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Auth Options -->
        <div class="pt-3 mt-3 border-t border-outline-variant">
            @auth
                <div class="px-2 mb-2">
                    <div class="font-semibold text-sm text-on-surface" x-data="{{ json_encode(['name' => auth()->user()->name ?? '']) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="text-xs text-on-surface-variant">{{ auth()->user()->email ?? '' }}</div>
                </div>

                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('profile')" wire:navigate>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            <span class="text-state-error font-semibold">{{ __('Log Out') }}</span>
                        </x-responsive-nav-link>
                    </button>
                </div>
            @else
                <div class="pt-1">
                    <a href="{{ route('login') }}" wire:navigate class="block w-full py-2 text-center text-sm font-semibold rounded-lg bg-primary text-on-primary hover:bg-primary-hover">
                        Masuk ke Akun
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
