<?php

use App\Models\ContactMessage;
use Livewire\Volt\Volt;

test('contact form page is displayed', function () {
    $this->get('/kontak')
        ->assertOk()
        ->assertSeeVolt('pages.contact');
});

test('a visitor can submit a valid contact message', function () {
    $component = Volt::test('pages.contact')
        ->set('name', 'Budi Santoso')
        ->set('email', 'budi@example.com')
        ->set('phone', '081234567890')
        ->set('subject', 'Konsultasi Aplikasi Web')
        ->set('message', 'Kami butuh aplikasi web custom untuk manajemen inventaris.')
        ->call('sendMessage');

    $component->assertHasNoErrors();
    $component->assertSet('submitted', true);

    $this->assertDatabaseHas('contact_messages', [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'subject' => 'Konsultasi Aplikasi Web',
    ]);
});

test('invalid contact submissions are rejected with friendly messages', function () {
    $component = Volt::test('pages.contact')
        ->set('name', 'A')
        ->set('email', 'bukan-email')
        ->set('message', 'Hi')
        ->call('sendMessage');

    $component->assertHasErrors(['name', 'email', 'message']);

    expect($component->errors()->first('name'))->toBe('Nama minimal 2 karakter.');
    expect($component->errors()->first('email'))->toBe('Format alamat email tidak valid.');

    $this->assertDatabaseCount('contact_messages', 0);
});

test('honeypot field silently drops bot submissions', function () {
    $component = Volt::test('pages.contact')
        ->set('name', 'Bot Account')
        ->set('email', 'bot@example.com')
        ->set('message', 'Ini pesan otomatis dari bot spam.')
        ->set('website', 'http://spam.example.com')
        ->call('sendMessage');

    // The bot is told it "succeeded" so it doesn't adapt...
    $component->assertHasNoErrors();
    $component->assertSet('submitted', true);

    // ...but nothing was actually persisted.
    expect(ContactMessage::count())->toBe(0);
});
