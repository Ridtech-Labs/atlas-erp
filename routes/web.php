<?php

use App\Core\Tenancy\Http\Controllers\SwitchActiveCompanyController;
use App\Core\Tenancy\Support\TenantContext;
use App\Http\Controllers\Finance\BillingRecordReceiptController;
use App\Http\Controllers\Operations\JobCardEvidenceController;
use App\Http\Controllers\Operations\WaybillEvidenceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Retain old bookmarks without exposing the obsolete Sprint 0 dashboard.
Route::redirect('dashboard', '/admin')
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

Route::get('admin/job-cards/{jobCard}/evidence/{media}', JobCardEvidenceController::class)
    ->middleware(['auth', 'verified'])
    ->name('atlas.job-cards.evidence.show');

Route::get('admin/waybills/{waybill}/evidence/{media}', WaybillEvidenceController::class)
    ->middleware(['auth', 'verified'])
    ->name('atlas.waybills.evidence.show');

require __DIR__.'/auth.php';
