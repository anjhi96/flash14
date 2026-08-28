<?php

use App\Models\User;

test('guests are redirected to login when visiting the admin dashboard', function () {
    $this->get('/admin/dashboard')
        ->assertRedirect('/login');
});

test('regular users are forbidden from the admin dashboard', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertForbidden();
});

test('admins can access the admin dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertOk()
        ->assertSeeVolt('admin.dashboard');
});
