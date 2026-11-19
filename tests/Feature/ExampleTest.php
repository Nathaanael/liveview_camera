<?php

test('the root redirects guests to the login page', function () {
    $this->get('/')->assertRedirect(route('login'));
});

test('the login page renders', function () {
    $this->get('/login')->assertOk()->assertSee(__('login.welcomeTitle'));
});
