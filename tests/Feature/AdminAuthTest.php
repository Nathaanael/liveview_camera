<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeAdmin(): User
{
    return User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@indofood.co.id',
        'password' => 'password',
        'role' => User::ROLE_ADMIN,
    ]);
}

test('guests are redirected to login from admin pages', function () {
    $this->get('/admin/dashboard')->assertRedirect(route('login'));
    $this->get('/admin/liveview')->assertRedirect(route('login'));
});

test('admin can log in and lands on the dashboard', function () {
    makeAdmin();

    $this->post('/login', ['email' => 'admin@indofood.co.id', 'password' => 'password'])
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticated();
});

test('wrong password is rejected', function () {
    makeAdmin();

    $this->from('/login')
        ->post('/login', ['email' => 'admin@indofood.co.id', 'password' => 'salah'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => __('login.failed')]);

    $this->assertGuest();
});

test('dashboard greets the logged in user by name', function () {
    $this->actingAs(makeAdmin())
        ->get('/admin/dashboard')
        ->assertOk()
        ->assertSee('Admin')
        ->assertSee(__('dashboard.greeting.welcome'))
        ->assertSee('Live View');
});

test('admin can open live view', function () {
    $this->actingAs(makeAdmin())->get('/admin/liveview')->assertOk();
});

test('logged in admin visiting login is sent to the dashboard', function () {
    $this->actingAs(makeAdmin())->get('/login')->assertRedirect(route('admin.dashboard'));
});

test('non admin users cannot log in yet', function () {
    User::factory()->create(['email' => 'user@indofood.co.id', 'password' => 'password', 'role' => 'user']);

    $this->post('/login', ['email' => 'user@indofood.co.id', 'password' => 'password'])
        ->assertSessionHasErrors(['email' => __('login.noAccess')]);

    $this->assertGuest();
});

test('non admin users get 403 on admin pages', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
});

test('logout ends the session', function () {
    $this->actingAs(makeAdmin())->post('/logout')->assertRedirect(route('login'));

    $this->assertGuest();
});

test('unused template pages are no longer routed', function () {
    foreach (['/ecommerce', '/calendar', '/profile', '/signin', '/basic-tables'] as $path) {
        $this->get($path)->assertNotFound();
    }
});
