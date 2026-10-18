@php
    use App\Core\Tenancy\Services\SupportTenantWorkspaceService;

    $user = auth()->user();
    $workspace = app(SupportTenantWorkspaceService::class);
@endphp

@if ($user && $workspace->canExit($user))
    <form method="POST" action="{{ route('atlas.support-workspace.exit') }}" class="atlas-topbar-workspace-exit">
        @csrf

        <span class="atlas-topbar-workspace-context">
            {{ $currentTenant?->name ?? 'Support workspace' }}
        </span>
        <button type="submit" class="atlas-topbar-workspace-exit-button">
            Exit Workspace
        </button>
    </form>
@endif
