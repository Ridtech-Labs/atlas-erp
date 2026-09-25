<?php

use App\Administration\Actions\Users\CreateUserAction;
use App\Administration\Actions\Users\RevokeUserInvitationAction;
use App\Administration\Actions\Users\SendUserInvitationAction;
use App\Administration\Enums\RoleName;
use App\Administration\Notifications\AtlasAccountSetupNotification;
use App\Core\Administration\Filament\Resources\Users\Pages\CreateUser;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use App\Operations\Models\Personnel;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Livewire\Volt\Volt;
use Tests\TestCase;

function invitationContext(TestCase $test): array
{
    app(RoleAndPermissionSeeder::class)->run();

    $tenant = Tenant::factory()->create(['name' => 'Kadmay Holdings']);
    $company = Company::factory()->for($tenant)->create(['name' => 'Kadmay']);
    $administrator = User::factory()->for($tenant)->create();
    $administrator->companies()->sync([$company->getKey()]);
    $administrator->assignRole(RoleName::CompanyAdministrator->value);
    $test->actingAs($administrator);
    session(['active_company_id' => $company->getKey()]);

    return [$tenant, $company, $administrator];
}

test('company administrator creates a scoped invited user and sends an account setup link', function () {
    Notification::fake();
    [$tenant, $company, $administrator] = invitationContext($this);

    $user = app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'first_name' => 'Invited',
        'last_name' => 'Operator',
        'email' => 'invited.operator@kadmay.test',
    ], [RoleName::OperationsManager->value], $administrator);

    expect($user->status)->toBe(UserStatus::Invited)
        ->and($user->tenant_id)->toBe($tenant->getKey())
        ->and($user->companies()->pluck('companies.id')->all())->toBe([$company->getKey()])
        ->and($user->hasRole(RoleName::OperationsManager->value))->toBeTrue()
        ->and($user->email_verified_at)->toBeNull()
        ->and(Hash::check('password', $user->password))->toBeFalse();

    Notification::assertSentTo($user, AtlasAccountSetupNotification::class);
});

test('company administrator sends an invitation through the Filament create user page', function () {
    Notification::fake();
    [$tenant, $company] = invitationContext($this);

    Livewire::test(CreateUser::class)
        ->set('data.first_name', 'Filament')
        ->set('data.last_name', 'Invite')
        ->set('data.email', 'filament.invite@kadmay.test')
        ->set('data.phone', '+233200000000')
        ->set('data.roles', [RoleName::DataEntryClerk->value])
        ->call('create')
        ->assertHasNoErrors();

    $user = User::query()->where('email', 'filament.invite@kadmay.test')->firstOrFail();

    expect($user->status)->toBe(UserStatus::Invited)
        ->and($user->tenant_id)->toBe($tenant->getKey())
        ->and($user->companies()->whereKey($company->getKey())->exists())->toBeTrue()
        ->and($user->hasRole(RoleName::DataEntryClerk->value))->toBeTrue();

    Notification::assertSentTo($user, AtlasAccountSetupNotification::class);
});

test('invited user cannot authenticate before setup and activates after using a valid setup token', function () {
    Notification::fake();
    [, , $administrator] = invitationContext($this);
    $user = app(CreateUserAction::class)->execute([
        'first_name' => 'Setup',
        'last_name' => 'User',
        'email' => 'setup.user@kadmay.test',
    ], [RoleName::DataEntryClerk->value], $administrator);

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'new-password')
        ->call('login')
        ->assertHasErrors(['form.email']);

    Notification::assertSentTo($user, AtlasAccountSetupNotification::class, function (AtlasAccountSetupNotification $notification) use ($user): bool {
        Volt::test('pages.auth.reset-password', ['token' => $notification->token])
            ->set('email', $user->email)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('resetPassword')
            ->assertHasNoErrors()
            ->assertRedirect('/admin');

        return true;
    });

    expect($user->refresh()->status)->toBe(UserStatus::Active)
        ->and($user->email_verified_at)->not->toBeNull();

    $this->assertAuthenticatedAs($user->fresh());
    $this->get('/admin')->assertOk();

    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'new-password')
        ->call('login')
        ->assertHasNoErrors();

    $this->assertAuthenticatedAs($user->fresh());
});

test('setup tokens cannot be reused and invalid tokens fail safely', function () {
    Notification::fake();
    [, , $administrator] = invitationContext($this);
    $user = app(CreateUserAction::class)->execute([
        'first_name' => 'Token',
        'last_name' => 'User',
        'email' => 'token.user@kadmay.test',
    ], [RoleName::StandardUser->value], $administrator);

    Notification::assertSentTo($user, AtlasAccountSetupNotification::class, function (AtlasAccountSetupNotification $notification) use ($user): bool {
        Volt::test('pages.auth.reset-password', ['token' => $notification->token])
            ->set('email', $user->email)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('resetPassword')
            ->assertHasNoErrors();

        Volt::test('pages.auth.reset-password', ['token' => $notification->token])
            ->set('email', $user->email)
            ->set('password', 'different-password')
            ->set('password_confirmation', 'different-password')
            ->call('resetPassword')
            ->assertHasErrors(['email']);

        return true;
    });
});

test('expired setup tokens leave the invited account inactive', function () {
    Notification::fake();
    [, , $administrator] = invitationContext($this);
    $user = app(CreateUserAction::class)->execute([
        'first_name' => 'Expired',
        'last_name' => 'User',
        'email' => 'expired.user@kadmay.test',
    ], [RoleName::StandardUser->value], $administrator);

    Notification::assertSentTo($user, AtlasAccountSetupNotification::class, function (AtlasAccountSetupNotification $notification) use ($user): bool {
        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->update(['created_at' => now()->subMinutes(61)]);

        Volt::test('pages.auth.reset-password', ['token' => $notification->token])
            ->set('email', $user->email)
            ->set('password', 'new-password')
            ->set('password_confirmation', 'new-password')
            ->call('resetPassword')
            ->assertHasErrors(['email']);

        return true;
    });

    expect($user->refresh()->status)->toBe(UserStatus::Invited);
});

test('company administrator can resend and revoke only their company invitation', function () {
    Notification::fake();
    [$tenant, $company, $administrator] = invitationContext($this);
    $user = app(CreateUserAction::class)->execute([
        'first_name' => 'Resend',
        'last_name' => 'User',
        'email' => 'resend.user@kadmay.test',
    ], [RoleName::StandardUser->value], $administrator);

    $setupToken = null;
    Notification::assertSentTo($user, AtlasAccountSetupNotification::class, function (AtlasAccountSetupNotification $notification) use (&$setupToken): bool {
        $setupToken = $notification->token;

        return true;
    });

    DB::table('password_reset_tokens')->where('email', $user->email)->update(['created_at' => now()->subMinutes(2)]);
    app(SendUserInvitationAction::class)->execute($user, $administrator, true);
    Notification::assertSentToTimes($user, AtlasAccountSetupNotification::class, 2);

    app(RevokeUserInvitationAction::class)->execute($user, $administrator);

    expect($user->refresh()->status)->toBe(UserStatus::Inactive)
        ->and(DB::table('password_reset_tokens')->where('email', $user->email)->exists())->toBeFalse();

    Volt::test('pages.auth.reset-password', ['token' => $setupToken])
        ->set('email', $user->email)
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('resetPassword')
        ->assertHasErrors(['email']);

    $otherCompany = Company::factory()->for($tenant)->create();
    $otherAdministrator = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $otherAdministrator->companies()->sync([$otherCompany->getKey()]);
    session(['active_company_id' => $otherCompany->getKey()]);

    expect(fn () => app(SendUserInvitationAction::class)->execute($user, $otherAdministrator, true))
        ->toThrow(BusinessException::class);
    expect(fn () => app(RevokeUserInvitationAction::class)->execute($user, $otherAdministrator))
        ->toThrow(BusinessException::class);
});

test('super administrator uses the same invitation flow for explicitly selected companies', function () {
    Notification::fake();
    app(RoleAndPermissionSeeder::class)->run();
    $tenant = Tenant::factory()->create();
    $company = Company::factory()->for($tenant)->create();
    $administrator = User::factory()->for($tenant)->create();
    $administrator->assignRole(RoleName::SuperAdministrator->value);

    $user = app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'first_name' => 'Platform',
        'last_name' => 'Invite',
        'email' => 'platform.invite@atlas.test',
    ], [RoleName::StandardUser->value], $administrator);

    expect($user->status)->toBe(UserStatus::Invited)
        ->and($user->companies()->whereKey($company->getKey())->exists())->toBeTrue();
    Notification::assertSentTo($user, AtlasAccountSetupNotification::class);
});

test('company administrator cannot invite a super administrator or another company user', function () {
    Notification::fake();
    [$tenant, $company, $administrator] = invitationContext($this);
    $otherCompany = Company::factory()->for($tenant)->create();

    expect(fn () => app(CreateUserAction::class)->execute([
        'company_id' => $otherCompany->getKey(),
        'first_name' => 'Cross',
        'last_name' => 'Company',
        'email' => 'cross.company@kadmay.test',
    ], [RoleName::StandardUser->value], $administrator))->toThrow(BusinessException::class);

    expect(fn () => app(CreateUserAction::class)->execute([
        'company_id' => $company->getKey(),
        'first_name' => 'Blocked',
        'last_name' => 'Administrator',
        'email' => 'blocked.admin@kadmay.test',
    ], [RoleName::SuperAdministrator->value], $administrator))->toThrow(BusinessException::class);
});

test('active user password reset remains a normal reset and personnel remain unrelated', function () {
    Notification::fake();
    [$tenant, $company] = invitationContext($this);
    $user = User::factory()->for($tenant)->create(['status' => UserStatus::Active]);
    $user->companies()->sync([$company->getKey()]);
    $userCount = User::query()->count();
    $personnel = Personnel::query()->create([
        'uuid' => (string) str()->uuid(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'first_name' => 'Personnel',
        'status' => 'active',
    ]);

    Volt::test('pages.auth.forgot-password')
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class);
    expect(User::query()->count())->toBe($userCount)
        ->and($personnel->user_id)->toBeNull();

    $this->get('/register')->assertNotFound();
});
