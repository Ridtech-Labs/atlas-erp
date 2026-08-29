@php
    use App\Administration\Services\AdministrationAccessService;
    use App\Core\Administration\Filament\Pages\Dashboard;
    use App\Core\Administration\Filament\Pages\ManageSettings;
    use App\Core\Administration\Filament\Pages\SystemHealth;
    use App\Core\Administration\Filament\Resources\Activities\ActivityResource;
    use App\Core\Administration\Filament\Resources\BillingBatches\BillingBatchResource;
    use App\Core\Administration\Filament\Resources\BillingRecords\BillingRecordResource;
    use App\Core\Administration\Filament\Resources\Clients\ClientResource;
    use App\Core\Administration\Filament\Resources\Jobs\JobResource;
    use App\Core\Administration\Filament\Resources\RateAgreements\RateAgreementResource;
    use App\Core\Administration\Filament\Resources\Roles\RoleResource;
    use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
    use App\Core\Administration\Filament\Resources\Users\UserResource;

    $adminUrl = null;

    if (TenantResource::canViewAny()) {
        $adminUrl = TenantResource::getUrl('index');
    } elseif (UserResource::canViewAny()) {
        $adminUrl = UserResource::getUrl('index');
    } elseif (RoleResource::canViewAny()) {
        $adminUrl = RoleResource::getUrl('index');
    } elseif (ActivityResource::canViewAny()) {
        $adminUrl = ActivityResource::getUrl('index');
    }

    $access = app(AdministrationAccessService::class);
    $isPlatformSession = $access->isPlatformSession(auth()->user());

    $groups = [
        [
            'label' => $isPlatformSession ? 'PLATFORM' : 'WORKSPACE',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'url' => Dashboard::canAccess() ? Dashboard::getUrl() : null,
                    'active' => request()->routeIs('filament.admin.pages.dashboard'),
                    'disabled' => ! Dashboard::canAccess(),
                    'icon' => 'dashboard',
                ],
            ],
        ],
        ...($isPlatformSession ? [[
            'label' => 'ADMINISTRATION',
            'items' => [
                [
                    'label' => 'Companies',
                    'url' => TenantResource::canViewAny() ? TenantResource::getUrl('index') : null,
                    'active' => request()->routeIs('filament.admin.resources.tenants.*'),
                    'disabled' => ! TenantResource::canViewAny(),
                    'icon' => 'administration',
                ],
                [
                    'label' => 'Users',
                    'url' => UserResource::canViewAny() ? UserResource::getUrl('index') : null,
                    'active' => request()->routeIs('filament.admin.resources.users.*'),
                    'disabled' => ! UserResource::canViewAny(),
                    'icon' => 'crm',
                ],
                [
                    'label' => 'Roles',
                    'url' => RoleResource::canViewAny() ? RoleResource::getUrl('index') : null,
                    'active' => request()->routeIs('filament.admin.resources.roles.*'),
                    'disabled' => ! RoleResource::canViewAny(),
                    'icon' => 'settings',
                ],
                [
                    'label' => 'Activity',
                    'url' => ActivityResource::canViewAny() ? ActivityResource::getUrl('index') : null,
                    'active' => request()->routeIs('filament.admin.resources.activities.*'),
                    'disabled' => ! ActivityResource::canViewAny(),
                    'icon' => 'reports',
                ],
                [
                    'label' => 'System Health',
                    'url' => SystemHealth::canAccess() ? SystemHealth::getUrl() : null,
                    'active' => request()->routeIs('filament.admin.pages.system-health'),
                    'disabled' => ! SystemHealth::canAccess(),
                    'icon' => 'settings',
                ],
            ],
        ]]: [[
            'label' => 'OPERATIONS',
            'items' => [
                [
                    'label' => 'CRM',
                    'url' => ClientResource::canViewAny() ? ClientResource::getUrl('index') : null,
                    'active' => request()->routeIs('filament.admin.resources.clients.*'),
                    'disabled' => ! ClientResource::canViewAny(),
                    'icon' => 'crm',
                ],
                [
                    'label' => 'Jobs',
                    'url' => JobResource::canViewAny() ? JobResource::getUrl('index') : null,
                    'active' => request()->routeIs('filament.admin.resources.jobs.*'),
                    'disabled' => ! JobResource::canViewAny(),
                    'icon' => 'jobs',
                ],
                ['label' => 'Fleet', 'url' => null, 'active' => false, 'disabled' => true, 'icon' => 'fleet'],
                ['label' => 'Inventory', 'url' => null, 'active' => false, 'disabled' => true, 'icon' => 'inventory'],
            ],
        ], [
            'label' => 'FINANCE',
            'items' => [
                [
                    'label' => 'Rate Agreements',
                    'url' => RateAgreementResource::canViewAny() ? RateAgreementResource::getUrl('index') : null,
                    'active' => request()->routeIs('filament.admin.resources.rate-agreements.*'),
                    'disabled' => false,
                    'icon' => 'finance',
                ],
                [
                    'label' => 'Billing Batches',
                    'url' => BillingBatchResource::canViewAny() ? BillingBatchResource::getUrl('index') : null,
                    'active' => request()->routeIs('filament.admin.resources.billing-batches.*'),
                    'disabled' => false,
                    'icon' => 'reports',
                ],
                [
                    'label' => 'Billing Records',
                    'url' => BillingRecordResource::canViewAny() ? BillingRecordResource::getUrl('index') : null,
                    'active' => request()->routeIs('filament.admin.resources.billing-records.*'),
                    'disabled' => false,
                    'icon' => 'finance',
                ],
            ],
        ], [
            'label' => 'SYSTEM',
            'items' => [
                [
                    'label' => 'Administration',
                    'url' => $adminUrl,
                    'active' => request()->routeIs('filament.admin.resources.tenants.*') || request()->routeIs('filament.admin.resources.users.*') || request()->routeIs('filament.admin.resources.roles.*') || request()->routeIs('filament.admin.resources.activities.*'),
                    'disabled' => blank($adminUrl),
                    'icon' => 'administration',
                ],
                [
                    'label' => 'Settings',
                    'url' => ManageSettings::canAccess() ? ManageSettings::getUrl() : null,
                    'active' => request()->routeIs('filament.admin.pages.manage-settings') || request()->routeIs('filament.admin.pages.system-health'),
                    'disabled' => ! ManageSettings::canAccess(),
                    'icon' => 'settings',
                ],
            ],
        ]]),
    ];

    $icons = [
        'dashboard' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>',
        'crm' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M20 8v6"/><path d="M23 11h-6"/></svg>',
        'jobs' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M14 6V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v2"/><path d="M18 6H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2Z"/><path d="M10 11h4"/></svg>',
        'fleet' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M3 13h13v5H3z"/><path d="M16 15h3l2 2v1h-5z"/><circle cx="7.5" cy="19.5" r="1.5"/><circle cx="17.5" cy="19.5" r="1.5"/></svg>',
        'inventory' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="m12 2 8 4.5v11L12 22l-8-4.5v-11L12 2Z"/><path d="m4 6.5 8 4.5 8-4.5"/></svg>',
        'finance' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 2v20"/><path d="M17 6H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
        'reports' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M5 19V9"/><path d="M12 19V5"/><path d="M19 19v-8"/></svg>',
        'administration' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg>',
        'settings' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06A1.65 1.65 0 0 0 15 19.4a1.65 1.65 0 0 0-1 1.5V21a2 2 0 0 1-4 0v-.09a1.65 1.65 0 0 0-1-1.5 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.5-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.5-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.5V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.5 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c0 .66.39 1.26 1 1.5H21a2 2 0 0 1 0 4h-.09c-.61.24-1 .84-1.5 1.5Z"/></svg>',
    ];
@endphp

<div class="atlas-sidebar-nav">
    @foreach ($groups as $group)
        @php
            $visibleItems = collect($group['items'])->filter(fn (array $item): bool => ! blank($item['url']) || $item['disabled']);
        @endphp

        @if ($visibleItems->isNotEmpty())
            <div class="atlas-sidebar-group">
                <div class="atlas-sidebar-group-label">{{ $group['label'] }}</div>

                <div class="atlas-sidebar-items">
                    @foreach ($visibleItems as $item)
                        @php
                            $tag = blank($item['url']) ? 'div' : 'a';
                        @endphp

                        <{{ $tag }}
                            @if (filled($item['url']))
                                href="{{ $item['url'] }}"
                            @endif
                            @class([
                                'atlas-sidebar-item',
                                'is-active' => $item['active'],
                                'is-disabled' => $item['disabled'],
                            ])
                        >
                            <span class="atlas-sidebar-item-icon">{!! $icons[$item['icon']] ?? '' !!}</span>
                            <span class="atlas-sidebar-item-label">{{ $item['label'] }}</span>
                        </{{ $tag }}>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>
