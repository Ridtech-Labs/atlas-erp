<?php

use App\Core\Tenancy\Support\TenantContext;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('health', function (TenantContext $tenantContext) {
    return response()->json([
        'application' => config('app.name'),
        'tenant' => $tenantContext->tenant()?->only(['id', 'uuid', 'name', 'slug']),
        'status' => 'ok',
    ]);
})->middleware(['auth', 'verified', 'can:viewHealth'])->name('health');

require __DIR__.'/auth.php';
