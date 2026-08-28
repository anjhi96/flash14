<?php

test('public pages are reachable', function (string $uri) {
    $this->get($uri)->assertOk();
})->with([
    '/',
    '/layanan',
    '/portfolio',
    '/tentang',
    '/kontak',
]);
