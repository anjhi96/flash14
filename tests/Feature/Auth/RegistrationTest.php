<?php

namespace Tests\Feature\Auth;

test('registration screen redirects to login', function () {
    $response = $this->get('/register');

    $response->assertRedirect(route('login'));
});
