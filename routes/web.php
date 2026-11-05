<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.home')->name('home');
Volt::route('/layanan', 'pages.services')->name('services');
Volt::route('/portfolio', 'pages.portfolio')->name('portfolio');
Volt::route('/tentang', 'pages.about')->name('about');
Volt::route('/kontak', 'pages.contact')->name('contact');
Volt::route('/blog', 'pages.blog.index')->name('blog.index');
Volt::route('/blog/{slug}', 'pages.blog.show')->name('blog.show');
Volt::route('/kebijakan-privasi', 'pages.privacy')->name('privacy');
Volt::route('/syarat-ketentuan', 'pages.terms')->name('terms');

Route::get('dashboard', function () {
    if (auth()->user()?->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Volt::route('/dashboard', 'admin.dashboard')->name('dashboard');
});

require __DIR__.'/auth.php';
