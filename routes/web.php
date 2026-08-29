<?php

use App\Core\Tenancy\Http\Controllers\SwitchActiveCompanyController;
use App\Core\Tenancy\Support\TenantContext;
use App\Http\Controllers\Finance\BillingRecordReceiptController;
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

Route::post('admin/company-context', SwitchActiveCompanyController::class)
    ->middleware(['auth', 'verified'])
    ->name('atlas.company-context.switch');

Route::get('admin/billing-records/{billingRecord}/receipts/{media}', BillingRecordReceiptController::class)
    ->middleware(['auth', 'verified'])
    ->name('atlas.billing-records.receipts.show');

require __DIR__.'/auth.php';
