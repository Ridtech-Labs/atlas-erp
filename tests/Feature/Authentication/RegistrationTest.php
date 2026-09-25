<?php

namespace Tests\Feature\Auth;

use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Route;

test('public registration is unavailable and cannot create a user', function () {
    Tenant::factory()->create();

    $this->get('/register')->assertNotFound();
    $this->post('/register', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    expect(Route::has('register'))->toBeFalse()
        ->and(User::query()->where('email', 'test@example.com')->exists())->toBeFalse();
});
