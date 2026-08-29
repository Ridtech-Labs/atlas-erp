<?php

declare(strict_types=1);

namespace App\Core\Administration\Services;

use App\Administration\Enums\PermissionName;
use App\Administration\Enums\RoleName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Pages\ManageSettings;
use App\Core\Administration\Filament\Pages\SystemHealth;
use App\Core\Administration\Filament\Resources\Clients\ClientResource;
use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use App\Core\Administration\Filament\Resources\Users\UserResource;
use App\Core\Administration\Filament\Resources\Waybills\WaybillResource;
use App\Core\Shared\Enums\TenantStatus;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Support\TenantContext;
use App\CRM\Enums\ClientStatus;
use App\CRM\Models\Client;
use App\Finance\Services\FinanceReportingService;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\Waybill;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class ExecutiveDashboardService
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly TenantContext $tenantContext,
    ) {}

    /**
     * @var array<string, array<string, mixed>>
     */
    private array $cache = [];

    /**
     * @return array<string, mixed>
     */
    public function forUser(User $user): array
    {
        $activeTenantId = $this->tenantContext->id() ?? 'platform';
        $key = "{$user->getKey()}:{$activeTenantId}";

        return $this->cache[$key] ??= $this->build($user);
    }

    /**
     * @return array<string, mixed>
     */
    private function build(User $user): array
    {
        if ($this->access->isPlatformSession($user)) {
            return $this->buildPlatform($user);
        }

        $tenant = $this->tenantContext->tenant() ?? $user->tenant;
        $company = $this->access->activeCompany($user);
        $today = CarbonImmutable::today();
        $monthStart = $today->startOfMonth();
        $previousMonthStart = $monthStart->subMonth();

        $canViewClients = $this->hasPermission($user, PermissionName::ClientsViewAny->value);
        $canCreateClients = $this->hasPermission($user, PermissionName::ClientsCreate->value);
        $canViewJobs = $this->hasPermission($user, PermissionName::JobsViewAny->value);
        $canCreateJobs = $this->hasPermission($user, PermissionName::JobsCreate->value);
        $canViewActivities = $this->hasPermission($user, PermissionName::ActivityLogsView->value);
        $canManageSettings = ManageSettings::canAccess();
        $dashboardProfile = $this->dashboardProfile($user);
        $showExport = $this->showsExecutiveExport($dashboardProfile);

        if (! $company instanceof Company) {
            return [
                'dashboard_profile' => $dashboardProfile,
                'greeting' => $this->greeting(),
                'tenant' => $tenant,
                'company' => null,
                'context_label' => 'Select a company',
                'today_label' => $today->format('l, j F Y'),
                'operational_status' => [
                    'tone' => 'info',
                    'text' => 'Company context required',
                    'detail' => 'Choose an authorized company before viewing operational data.',
                ],
                'summary' => 'Operational dashboards, jobs, and clients activate when a company context is selected inside the current tenant.',
                'primary_action' => null,
                'kpis' => [],
                'attention_items' => [],
                'approval_queue' => collect(),
                'system_alerts' => [],
                'work_items' => [
                    'heading' => 'Company Selection Required',
                    'description' => 'Choose a company inside the current tenant before Atlas loads operational data.',
                    'items' => collect(),
                    'mode' => 'restricted',
                ],
                'recent_clients' => collect(),
                'recent_activity' => [
                    'items' => collect(),
                    'restricted' => true,
                ],
                'quick_actions' => [],
                'show_export' => false,
                'can' => [
                    'view_clients' => false,
                    'create_clients' => false,
                    'view_jobs' => false,
                    'create_jobs' => false,
                    'view_activity' => false,
                    'manage_settings' => false,
                    'view_approval_queue' => false,
                ],
            ];
        }

        $companyId = $company->getKey();

        return [
            'dashboard_profile' => $dashboardProfile,
            'greeting' => $this->greeting(),
            'tenant' => $tenant,
            'company' => $company,
            'context_label' => $company->name,
            'today_label' => $today->format('l, j F Y'),
            'operational_status' => $this->operationalStatus($companyId, $today, $canViewJobs),
            'summary' => $this->summary($companyId, $today, $canViewJobs, $canViewClients, $dashboardProfile),
            'primary_action' => $this->primaryAction($canCreateJobs, $canCreateClients, $canViewJobs, $canViewClients),
            'kpis' => $this->kpis($companyId, $today, $monthStart, $previousMonthStart, $canViewClients, $canViewJobs, $dashboardProfile, $user),
            'attention_items' => $this->attentionItems($user, $companyId, $today, $canViewClients, $canViewJobs, $dashboardProfile),
            'approval_queue' => $this->approvalQueue($companyId, $user, $dashboardProfile),
            'system_alerts' => $this->systemAlerts($companyId, $today, $canViewClients, $canViewJobs, $dashboardProfile),
            'work_items' => $this->workItems($companyId, $today, $canViewJobs, $dashboardProfile, $user),
            'recent_clients' => $this->recentClients($companyId, $canViewClients),
            'recent_activity' => $this->recentActivity($user, $companyId, $canViewActivities),
            'quick_actions' => $this->quickActions($canCreateClients, $canCreateJobs, $canViewClients, $canViewJobs, $canManageSettings, $dashboardProfile),
            'show_export' => $showExport,
            'can' => [
                'view_clients' => $canViewClients,
                'create_clients' => $canCreateClients,
                'view_jobs' => $canViewJobs,
                'create_jobs' => $canCreateJobs,
                'view_activity' => $canViewActivities,
                'manage_settings' => $canManageSettings,
                'view_approval_queue' => $this->canViewApprovalQueue($user, $dashboardProfile),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPlatform(User $user): array
    {
        $today = CarbonImmutable::today();
        $canViewActivities = $this->hasPermission($user, PermissionName::ActivityLogsView->value);
        $tenantCount = Tenant::query()->count();
        $activeTenantCount = Tenant::query()->where('status', TenantStatus::Active->value)->count();
        $suspendedTenantCount = Tenant::query()->where('status', TenantStatus::Suspended->value)->count();
        $userCount = User::query()->count();

        return [
            'greeting' => $this->greeting(),
            'tenant' => null,
            'dashboard_profile' => 'platform',
            'context_label' => 'Platform Administration',
            'today_label' => $today->format('l, j F Y'),
            'operational_status' => [
                'tone' => 'info',
                'text' => 'Platform administration session',
                'detail' => "{$activeTenantCount} active tenant".($activeTenantCount === 1 ? '' : 's').' on Atlas ERP',
            ],
            'summary' => 'Platform metrics and tenant administration are shown here by default. Enter a tenant explicitly for support before viewing operational records.',
            'primary_action' => TenantResource::canViewAny()
                ? ['label' => 'Review tenants', 'url' => TenantResource::getUrl('index')]
                : null,
            'kpis' => [
                [
                    'label' => 'Tenants',
                    'value' => (string) $tenantCount,
                    'description' => "{$activeTenantCount} active workspaces",
                    'trend' => null,
                    'status' => 'info',
                    'icon' => 'clients',
                ],
                [
                    'label' => 'Platform Users',
                    'value' => (string) $userCount,
                    'description' => 'All tenant and platform accounts',
                    'trend' => null,
                    'status' => 'success',
                    'icon' => 'operations',
                ],
                [
                    'label' => 'Suspended Tenants',
                    'value' => (string) $suspendedTenantCount,
                    'description' => $suspendedTenantCount === 0 ? 'No workspace suspensions' : 'Requires platform review',
                    'trend' => null,
                    'status' => $suspendedTenantCount > 0 ? 'warning' : 'info',
                    'icon' => 'approval',
                ],
                [
                    'label' => 'System Health',
                    'value' => '—',
                    'description' => 'Use System Health for service diagnostics',
                    'trend' => null,
                    'status' => 'accent',
                    'icon' => 'calendar',
                ],
                [
                    'label' => 'Audit Events',
                    'value' => (string) Activity::query()->count(),
                    'description' => 'Platform-wide activity log volume',
                    'trend' => null,
                    'status' => 'info',
                    'icon' => 'check',
                ],
                [
                    'label' => 'Revenue MTD',
                    'value' => '—',
                    'description' => 'Finance reporting is available inside an authorized tenant workspace.',
                    'trend' => null,
                    'status' => 'success',
                    'icon' => 'revenue',
                ],
            ],
            'attention_items' => [],
            'approval_queue' => collect(),
            'system_alerts' => array_values(array_filter([
                $suspendedTenantCount > 0 ? [
                    'tone' => 'warning',
                    'title' => 'Suspended tenants detected',
                    'detail' => "{$suspendedTenantCount} tenant".($suspendedTenantCount === 1 ? '' : 's').' currently suspended.',
                    'url' => TenantResource::canViewAny() ? TenantResource::getUrl('index') : '#',
                ] : null,
                SystemHealth::canAccess() ? [
                    'tone' => 'info',
                    'title' => 'Review platform health',
                    'detail' => 'Open the system health page for queue, cache, and service diagnostics.',
                    'url' => SystemHealth::getUrl(),
                ] : null,
            ])),
            'work_items' => [
                'heading' => 'Platform Overview',
                'description' => 'Tenant jobs and approvals are intentionally hidden until a support tenant session is entered explicitly.',
                'items' => collect(),
                'mode' => 'restricted',
            ],
            'recent_clients' => collect(),
            'recent_activity' => $this->platformRecentActivity($canViewActivities),
            'quick_actions' => array_values(array_filter([
                TenantResource::canViewAny() ? [
                    'title' => 'Tenants',
                    'description' => 'Review subscribed workspaces.',
                    'icon' => 'clients',
                    'url' => TenantResource::getUrl('index'),
                ] : null,
                UserResource::canViewAny() ? [
                    'title' => 'Users',
                    'description' => 'Review platform and tenant users.',
                    'icon' => 'operations',
                    'url' => UserResource::getUrl('index'),
                ] : null,
                SystemHealth::canAccess() ? [
                    'title' => 'Health',
                    'description' => 'Inspect service health and diagnostics.',
                    'icon' => 'settings',
                    'url' => SystemHealth::getUrl(),
                ] : null,
            ])),
            'show_export' => true,
            'can' => [
                'view_clients' => false,
                'create_clients' => false,
                'view_jobs' => false,
                'create_jobs' => false,
                'view_activity' => $canViewActivities,
                'manage_settings' => false,
                'view_approval_queue' => false,
            ],
        ];
    }

    private function greeting(): string
    {
        $hour = now()->hour;

        return match (true) {
            $hour < 12 => 'Good morning',
            $hour < 18 => 'Good afternoon',
            default => 'Good evening',
        };
    }

    /**
     * @return array{tone: string, text: string, detail: string}
     */
    private function operationalStatus(int $tenantId, CarbonImmutable $today, bool $canViewJobs): array
    {
        if (! $canViewJobs) {
            return [
                'tone' => 'info',
                'text' => 'Workspace access is limited',
                'detail' => 'Operational health will appear here when your role can access jobs.',
            ];
        }

        $activeJobs = $this->jobQuery($tenantId)
            ->whereIn('status', [
                JobStatus::Scheduled->value,
                JobStatus::InProgress->value,
                JobStatus::OnHold->value,
            ])
            ->count();

        $overdueJobs = $this->jobQuery($tenantId)
            ->whereIn('status', [
                JobStatus::Approved->value,
                JobStatus::Scheduled->value,
                JobStatus::InProgress->value,
                JobStatus::OnHold->value,
            ])
            ->whereDate('planned_end_date', '<', $today)
            ->count();

        if ($overdueJobs > 0) {
            return [
                'tone' => 'warning',
                'text' => "{$overdueJobs} item".($overdueJobs === 1 ? '' : 's').' require attention',
                'detail' => "{$activeJobs} active job".($activeJobs === 1 ? '' : 's').' today',
            ];
        }

        return [
            'tone' => 'success',
            'text' => 'All systems operational',
            'detail' => "{$activeJobs} active job".($activeJobs === 1 ? '' : 's').' today',
        ];
    }

    private function summary(int $companyId, CarbonImmutable $today, bool $canViewJobs, bool $canViewClients, string $dashboardProfile): string
    {
        if ($dashboardProfile === 'data_entry') {
            $draftJobs = $this->jobQuery($companyId)->where('status', JobStatus::Draft->value)->count();
            $recordsNeedingEntry = $this->jobsNeedingOperationalRecordCount($companyId);
            $returnedRecords = $this->returnedOperationalRecordsCount($companyId);

            return match (true) {
                $draftJobs > 0 => "{$draftJobs} draft job".($draftJobs === 1 ? '' : 's').' can be completed or corrected before handover to Operations.',
                $recordsNeedingEntry > 0 => "{$recordsNeedingEntry} job".($recordsNeedingEntry === 1 ? '' : 's').' need operational record entry from completed field work.',
                $returnedRecords > 0 => "{$returnedRecords} operational record".($returnedRecords === 1 ? '' : 's').' need correction before Accounts review can continue.',
                default => 'Your workspace is clear. Create or update draft operational records as new work comes in.',
            };
        }

        if ($dashboardProfile === 'operations') {
            $readyForDeployment = $this->jobQuery($companyId)->where('status', JobStatus::Scheduled->value)->count();
            $inProgress = $this->jobQuery($companyId)->where('status', JobStatus::InProgress->value)->count();

            return match (true) {
                $readyForDeployment > 0 => "{$readyForDeployment} job".($readyForDeployment === 1 ? '' : 's').' are ready for deployment and awaiting operational follow-through.',
                $inProgress > 0 => "{$inProgress} active job".($inProgress === 1 ? '' : 's').' are currently underway across the company workspace.',
                default => 'Operations planning is up to date. Review draft and active jobs as the next work allocations arrive.',
            };
        }

        if (! $canViewJobs && ! $canViewClients) {
            return 'Your workspace is ready. Operational details will appear here when your role can access them.';
        }

        $fragments = [];

        if ($canViewJobs) {
            $jobsToday = $this->jobQuery($companyId)->whereDate('planned_start_date', $today)->count();
            $pending = $this->jobQuery($companyId)->where('status', JobStatus::PendingApproval->value)->count();

            if ($jobsToday > 0) {
                $fragments[] = "{$jobsToday} job".($jobsToday === 1 ? '' : 's').' scheduled for today';
            }

            if ($pending > 0) {
                $fragments[] = "{$pending} approval".($pending === 1 ? '' : 's').' waiting';
            }
        }

        if ($canViewClients) {
            $activeClients = $this->clientQuery($companyId)
                ->where('status', ClientStatus::Active->value)
                ->count();

            if ($activeClients > 0) {
                $fragments[] = "{$activeClients} active client".($activeClients === 1 ? '' : 's').' in the workspace';
            }
        }

        if ($fragments === []) {
            return 'No operational blockers are visible right now. Use the dashboard to start work and track today\'s activity.';
        }

        return ucfirst(implode(' and ', $fragments)).'.';
    }

    /**
     * @return array<string, string>|null
     */
    private function primaryAction(bool $canCreateJobs, bool $canCreateClients, bool $canViewJobs, bool $canViewClients): ?array
    {
        if ($canCreateJobs) {
            return [
                'label' => 'New job',
                'url' => JobResource::getUrl('create'),
            ];
        }

        if ($canCreateClients) {
            return [
                'label' => 'New client',
                'url' => ClientResource::getUrl('create'),
            ];
        }

        if ($canViewJobs) {
            return [
                'label' => 'Review jobs',
                'url' => JobResource::getUrl('index'),
            ];
        }

        if ($canViewClients) {
            return [
                'label' => 'Review clients',
                'url' => ClientResource::getUrl('index'),
            ];
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function kpis(int $tenantId, CarbonImmutable $today, CarbonImmutable $monthStart, CarbonImmutable $previousMonthStart, bool $canViewClients, bool $canViewJobs, string $dashboardProfile, User $user): array
    {
        if ($dashboardProfile === 'data_entry') {
            return $this->dataEntryKpis($tenantId, $today, $monthStart, $user);
        }

        if ($dashboardProfile === 'operations') {
            return $this->operationsKpis($tenantId, $today, $monthStart);
        }

        $totalClients = $canViewClients ? $this->clientQuery($tenantId)->count() : null;
        $newClientsThisMonth = $canViewClients
            ? $this->clientQuery($tenantId)
                ->where('created_at', '>=', $monthStart)
                ->count()
            : null;
        $newClientsPreviousMonth = $canViewClients
            ? $this->clientQuery($tenantId)
                ->whereBetween('created_at', [$previousMonthStart, $monthStart])
                ->count()
            : null;

        $activeJobs = $canViewJobs
            ? $this->jobQuery($tenantId)
                ->whereIn('status', [
                    JobStatus::Scheduled->value,
                    JobStatus::InProgress->value,
                    JobStatus::OnHold->value,
                ])->count()
            : null;
        $activeSites = $canViewJobs
            ? $this->jobQuery($tenantId)
                ->whereIn('status', [
                    JobStatus::Scheduled->value,
                    JobStatus::InProgress->value,
                    JobStatus::OnHold->value,
                ])
                ->distinct('client_site_id')
                ->count('client_site_id')
            : null;

        $pendingApprovals = $canViewJobs
            ? $this->jobQuery($tenantId)->where('status', JobStatus::PendingApproval->value)->count()
            : null;
        $oldestPendingApproval = $canViewJobs
            ? $this->jobQuery($tenantId)
                ->where('status', JobStatus::PendingApproval->value)
                ->oldest('updated_at')
                ->first()
            : null;

        $jobsToday = $canViewJobs
            ? $this->jobQuery($tenantId)->whereDate('planned_start_date', $today)->count()
            : null;
        $jobSitesToday = $canViewJobs
            ? $this->jobQuery($tenantId)
                ->whereDate('planned_start_date', $today)
                ->distinct('client_site_id')
                ->count('client_site_id')
            : null;

        $completedThisMonth = $canViewJobs
            ? $this->jobQuery($tenantId)
                ->where('status', JobStatus::Completed->value)
                ->where('completed_at', '>=', $monthStart)
                ->count()
            : null;
        $completedPreviousMonth = $canViewJobs
            ? $this->jobQuery($tenantId)
                ->where('status', JobStatus::Completed->value)
                ->whereBetween('completed_at', [$previousMonthStart, $monthStart])
                ->count()
            : null;

        return [
            [
                'label' => 'Total Clients',
                'value' => $canViewClients ? (string) $totalClients : '—',
                'description' => $canViewClients ? ($newClientsThisMonth === 0 ? 'No new clients this month' : "+{$newClientsThisMonth} this month") : 'Not available for your role.',
                'trend' => $this->trendLabel($newClientsThisMonth, $newClientsPreviousMonth),
                'status' => 'info',
                'icon' => 'clients',
            ],
            [
                'label' => 'Active Jobs',
                'value' => $canViewJobs ? (string) $activeJobs : '—',
                'description' => $canViewJobs ? "Across {$activeSites} site".($activeSites === 1 ? '' : 's') : 'Not available for your role.',
                'status' => 'success',
                'icon' => 'operations',
            ],
            [
                'label' => 'Pending Approvals',
                'value' => $canViewJobs
                    ? (string) $pendingApprovals
                    : '—',
                'description' => $canViewJobs
                    ? ($oldestPendingApproval?->updated_at ? 'Oldest: '.$oldestPendingApproval->updated_at->diffForHumans() : 'Nothing awaiting review')
                    : 'Not available for your role.',
                'trend' => null,
                'status' => 'warning',
                'icon' => 'approval',
            ],
            [
                'label' => 'Jobs Today',
                'value' => $canViewJobs ? (string) $jobsToday : '—',
                'description' => $canViewJobs ? "Across {$jobSitesToday} site".($jobSitesToday === 1 ? '' : 's') : 'Not available for your role.',
                'trend' => null,
                'status' => 'accent',
                'icon' => 'calendar',
            ],
            [
                'label' => 'Completed MTD',
                'value' => $canViewJobs
                    ? (string) $completedThisMonth
                    : '—',
                'description' => $canViewJobs ? 'vs '.($completedPreviousMonth ?? 0).' last month' : 'Not available for your role.',
                'trend' => $this->trendLabel($completedThisMonth, $completedPreviousMonth),
                'status' => 'info',
                'icon' => 'check',
            ],
            [
                'label' => 'Revenue MTD',
                'value' => $this->financeRevenueMtd($tenantId, $user, $dashboardProfile),
                'description' => $dashboardProfile === 'finance' ? 'Paid or closed Billing Records' : 'Finance reporting is available to authorized users.',
                'trend' => null,
                'status' => 'success',
                'icon' => 'revenue',
            ],
        ];
    }

    /**
     * @return Collection<int, Job>
     */
    private function approvalQueue(int $tenantId, User $user, string $dashboardProfile): Collection
    {
        if (! $this->canViewApprovalQueue($user, $dashboardProfile)) {
            return collect();
        }

        return $this->jobQuery($tenantId)
            ->with(['client'])
            ->where('status', JobStatus::PendingApproval->value)
            ->orderByRaw('planned_start_date asc nulls last')
            ->oldest('updated_at')
            ->limit(4)
            ->get();
    }

    /**
     * @return list<array<string, string>>
     */
    private function systemAlerts(int $tenantId, CarbonImmutable $today, bool $canViewClients, bool $canViewJobs, string $dashboardProfile): array
    {
        if ($dashboardProfile === 'data_entry') {
            return $this->dataEntryAlerts($tenantId);
        }

        $alerts = [];

        if ($canViewJobs) {
            $overdueJob = $this->jobQuery($tenantId)
                ->whereIn('status', [
                    JobStatus::Approved->value,
                    JobStatus::Scheduled->value,
                    JobStatus::InProgress->value,
                    JobStatus::OnHold->value,
                ])
                ->whereDate('planned_end_date', '<', $today)
                ->orderBy('planned_end_date')
                ->first();

            if ($overdueJob instanceof Job) {
                $plannedEndDate = filled($overdueJob->planned_end_date)
                    ? CarbonImmutable::parse((string) $overdueJob->planned_end_date)
                    : null;

                $alerts[] = [
                    'tone' => 'danger',
                    'title' => $overdueJob->title.' overdue',
                    'detail' => 'Planned finish date passed '.($plannedEndDate?->diffForHumans() ?? 'recently').'.',
                    'url' => $this->jobUrl($overdueJob),
                ];
            }

            $unscheduledJob = $this->jobQuery($tenantId)
                ->whereIn('status', [JobStatus::Approved->value, JobStatus::Scheduled->value])
                ->whereNull('planned_start_date')
                ->latest('updated_at')
                ->first();

            if ($unscheduledJob instanceof Job) {
                $alerts[] = [
                    'tone' => 'warning',
                    'title' => 'Scheduling required',
                    'detail' => $unscheduledJob->title.' still needs a planned start date.',
                    'url' => $this->jobUrl($unscheduledJob),
                ];
            }
        }

        if ($canViewClients) {
            $incompleteClient = $this->clientQuery($tenantId)
                ->where(function ($query): void {
                    $query->whereNull('email')
                        ->orWhereNull('phone')
                        ->orWhereNull('physical_address');
                })
                ->latest('updated_at')
                ->first();

            if ($incompleteClient instanceof Client) {
                $alerts[] = [
                    'tone' => 'info',
                    'title' => 'Client profile incomplete',
                    'detail' => $incompleteClient->display_name.' is missing key contact or location details.',
                    'url' => $this->clientUrl($incompleteClient),
                ];
            }
        }

        return array_slice($alerts, 0, 3);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function attentionItems(User $user, int $tenantId, CarbonImmutable $today, bool $canViewClients, bool $canViewJobs, string $dashboardProfile): array
    {
        if ($dashboardProfile === 'data_entry') {
            return $this->dataEntryAttentionItems($tenantId);
        }

        if ($dashboardProfile === 'operations') {
            return $this->operationsAttentionItems($tenantId, $today);
        }

        $items = [];

        if ($canViewJobs) {
            $overdueJobs = $this->jobQuery($tenantId)
                ->with(['client', 'site'])
                ->whereIn('status', [
                    JobStatus::Approved->value,
                    JobStatus::Scheduled->value,
                    JobStatus::InProgress->value,
                    JobStatus::OnHold->value,
                ])
                ->whereDate('planned_end_date', '<', $today)
                ->orderBy('planned_end_date')
                ->limit(2)
                ->get();

            foreach ($overdueJobs as $job) {
                $items[] = [
                    'title' => $job->title,
                    'description' => 'Past planned finish date for '.$this->jobReference($job).'.',
                    'meta' => $job->client->display_name ?? 'No client linked',
                    'status' => 'danger',
                    'action_label' => 'Review job',
                    'url' => $this->jobUrl($job),
                ];
            }

            $pendingApprovalJobs = $this->jobQuery($tenantId)
                ->with(['client', 'site'])
                ->where('status', JobStatus::PendingApproval->value)
                ->orderByRaw('planned_start_date asc nulls last')
                ->latest('updated_at')
                ->limit(2)
                ->get();

            foreach ($pendingApprovalJobs as $job) {
                $items[] = [
                    'title' => $job->title,
                    'description' => 'Awaiting approval before the team can proceed.',
                    'meta' => $job->client->display_name ?? 'No client linked',
                    'status' => 'warning',
                    'action_label' => 'Open approval item',
                    'url' => $this->jobUrl($job),
                ];
            }

            $unscheduledJobs = $this->jobQuery($tenantId)
                ->with(['client'])
                ->whereIn('status', [
                    JobStatus::Approved->value,
                    JobStatus::Scheduled->value,
                ])
                ->whereNull('planned_start_date')
                ->latest('updated_at')
                ->limit(1)
                ->get();

            foreach ($unscheduledJobs as $job) {
                $items[] = [
                    'title' => $job->title,
                    'description' => 'Missing a planned start date.',
                    'meta' => $job->client->display_name ?? 'No client linked',
                    'status' => 'info',
                    'action_label' => 'Add schedule',
                    'url' => $this->jobUrl($job),
                ];
            }
        }

        if ($canViewClients) {
            $incompleteClients = $this->clientQuery($tenantId)
                ->where(function ($query): void {
                    $query->whereNull('email')
                        ->orWhereNull('phone')
                        ->orWhereNull('physical_address');
                })
                ->latest('updated_at')
                ->limit(2)
                ->get();

            foreach ($incompleteClients as $client) {
                $items[] = [
                    'title' => $client->display_name,
                    'description' => 'Client profile is missing contact or location details.',
                    'meta' => $client->client_code,
                    'status' => 'accent',
                    'action_label' => 'Complete profile',
                    'url' => $this->clientUrl($client),
                ];
            }
        }

        return array_slice($items, 0, 6);
    }

    /**
     * @return array<string, mixed>
     */
    private function workItems(int $tenantId, CarbonImmutable $today, bool $canViewJobs, string $dashboardProfile, User $user): array
    {
        if ($dashboardProfile === 'data_entry') {
            return $this->dataEntryWorkItems($tenantId);
        }

        if ($dashboardProfile === 'operations') {
            return $this->operationsWorkItems($tenantId, $today);
        }

        if (! $canViewJobs) {
            return [
                'heading' => 'Upcoming Work',
                'description' => 'Job scheduling is not available for your role.',
                'items' => collect(),
                'mode' => 'restricted',
            ];
        }

        $upcoming = $this->jobQuery($tenantId)
            ->with(['client', 'site'])
            ->whereNotNull('planned_start_date')
            ->whereDate('planned_start_date', '>=', $today)
            ->orderBy('planned_start_date')
            ->limit(5)
            ->get();

        if ($upcoming->isNotEmpty()) {
            return [
                'heading' => 'Upcoming Work',
                'description' => 'The next scheduled jobs across the current company workspace.',
                'items' => $upcoming,
                'mode' => 'upcoming',
            ];
        }

        return [
            'heading' => 'Recent Jobs',
            'description' => 'Recent operational jobs while the scheduling queue is still light.',
            'items' => $this->jobQuery($tenantId)
                ->with(['client', 'site'])
                ->latest('updated_at')
                ->limit(5)
                ->get(),
            'mode' => 'recent',
        ];
    }

    /**
     * @return Collection<int, Client>
     */
    private function recentClients(int $tenantId, bool $canViewClients): Collection
    {
        if (! $canViewClients) {
            return collect();
        }

        return $this->clientQuery($tenantId)
            ->withCount(['jobs', 'contacts', 'sites'])
            ->latest()
            ->limit(5)
            ->get();
    }

    /**
     * @return array<string, mixed>
     */
    private function recentActivity(User $user, int $companyId, bool $canViewActivities): array
    {
        if (! $canViewActivities) {
            return [
                'items' => collect(),
                'restricted' => true,
            ];
        }

        $items = Activity::query()
            ->with(['causer', 'subject'])
            ->latest()
            ->limit(24)
            ->get()
            ->filter(function (Activity $activity) use ($companyId): bool {
                $loggedCompanyId = (int) ($activity->properties['company_id'] ?? 0);

                if ($loggedCompanyId > 0) {
                    return $loggedCompanyId === $companyId;
                }

                return (int) data_get($activity->subject, 'company_id', 0) === $companyId;
            })
            ->take(8)
            ->map(function (Activity $activity) use ($user): array {
                $subject = $activity->subject;

                return [
                    'event' => $activity->event ? str($activity->event)->replace('.', ' ')->headline()->toString() : 'Activity',
                    'description' => $activity->description,
                    'actor' => $activity->causer instanceof User ? $activity->causer->full_name : 'System',
                    'subject_label' => $this->subjectLabel($subject),
                    'timestamp' => $activity->created_at?->diffForHumans() ?? 'Recently',
                    'status' => $this->activityStatus((string) $activity->event),
                    'url' => $this->subjectUrl($subject, $user),
                ];
            });

        return [
            'items' => $items,
            'restricted' => false,
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    private function quickActions(bool $canCreateClients, bool $canCreateJobs, bool $canViewClients, bool $canViewJobs, bool $canManageSettings, string $dashboardProfile): array
    {
        if ($dashboardProfile === 'data_entry') {
            return $this->dataEntryQuickActions($canCreateJobs, $canViewJobs, $canViewClients);
        }

        $actions = [];

        if ($canCreateClients) {
            $actions[] = [
                'title' => 'New Client',
                'description' => 'Create a customer workspace.',
                'icon' => 'clients',
                'url' => ClientResource::getUrl('create'),
            ];
        }

        if ($canCreateJobs) {
            $actions[] = [
                'title' => 'New Job',
                'description' => 'Capture new operational work.',
                'icon' => 'operations',
                'url' => JobResource::getUrl('create'),
            ];
        }

        if ($canViewClients) {
            $actions[] = [
                'title' => 'CRM',
                'description' => 'Open the client workspace.',
                'icon' => 'crm',
                'url' => ClientResource::getUrl('index'),
            ];
        }

        if ($canViewJobs) {
            $actions[] = [
                'title' => 'Schedule',
                'description' => 'Open the operations queue.',
                'icon' => 'calendar',
                'url' => JobResource::getUrl('index'),
            ];
        }

        if ($canManageSettings) {
            $actions[] = [
                'title' => 'Settings',
                'description' => 'Review workspace configuration.',
                'icon' => 'settings',
                'url' => ManageSettings::getUrl(),
            ];
        }

        return array_slice($actions, 0, 6);
    }

    /**
     * @return array<string, mixed>
     */
    private function platformRecentActivity(bool $canViewActivities): array
    {
        if (! $canViewActivities) {
            return [
                'items' => collect(),
                'restricted' => true,
            ];
        }

        return [
            'items' => Activity::query()
                ->with(['causer', 'subject'])
                ->latest()
                ->limit(8)
                ->get()
                ->map(fn (Activity $activity): array => [
                    'event' => $activity->event ? str($activity->event)->replace('.', ' ')->headline()->toString() : 'Activity',
                    'description' => $activity->description,
                    'actor' => $activity->causer instanceof User ? $activity->causer->full_name : 'System',
                    'subject_label' => $this->subjectLabel($activity->subject),
                    'timestamp' => $activity->created_at?->diffForHumans() ?? 'Recently',
                    'status' => $this->activityStatus((string) $activity->event),
                    'url' => null,
                ]),
            'restricted' => false,
        ];
    }

    private function subjectLabel(mixed $subject): string
    {
        if ($subject instanceof Job) {
            return $this->jobReference($subject);
        }

        if ($subject instanceof Client) {
            return $subject->display_name;
        }

        if ($subject instanceof Model) {
            return class_basename($subject);
        }

        return 'System';
    }

    private function activityStatus(string $event): string
    {
        return match (true) {
            str_contains($event, 'deleted'), str_contains($event, 'cancelled') => 'danger',
            str_contains($event, 'created') => 'success',
            str_contains($event, 'approved'), str_contains($event, 'completed') => 'info',
            default => 'accent',
        };
    }

    private function subjectUrl(mixed $subject, User $user): ?string
    {
        if ($subject instanceof Job && Gate::forUser($user)->allows('view', $subject)) {
            return $this->jobUrl($subject);
        }

        if ($subject instanceof Client && Gate::forUser($user)->allows('view', $subject)) {
            return $this->clientUrl($subject);
        }

        return null;
    }

    private function jobUrl(Job $job): string
    {
        return JobResource::getUrl('view', ['record' => $job]);
    }

    private function clientUrl(Client $client): string
    {
        return ClientResource::getUrl('view', ['record' => $client]);
    }

    private function jobReference(Job $job): string
    {
        return $job->job_number ?: $job->title;
    }

    /**
     * @return Builder<Client>
     */
    private function clientQuery(?int $companyId): Builder
    {
        return Client::query()->where('company_id', $companyId);
    }

    /**
     * @return Builder<Job>
     */
    private function jobQuery(?int $companyId): Builder
    {
        return Job::query()->where('company_id', $companyId);
    }

    private function dashboardProfile(User $user): string
    {
        return match (true) {
            $user->hasRole(RoleName::DataEntryClerk->value) => 'data_entry',
            $user->hasRole(RoleName::OperationsManager->value) => 'operations',
            $user->hasRole(RoleName::FinanceManager->value), $user->hasRole(RoleName::Accountant->value) => 'finance',
            $user->hasRole(RoleName::CompanyAdministrator->value) => 'company_admin',
            default => 'restricted',
        };
    }

    private function financeRevenueMtd(int $companyId, User $user, string $dashboardProfile): string
    {
        if ($dashboardProfile !== 'finance' && $dashboardProfile !== 'company_admin') {
            return '—';
        }

        if (! $this->hasPermission($user, PermissionName::BillingRecordsView->value)) {
            return '—';
        }

        $tenantId = $this->tenantContext->id() ?? $user->tenant_id;

        if (! is_int($tenantId)) {
            return '—';
        }

        $totals = app(FinanceReportingService::class)->revenueMtd($tenantId, $companyId);

        if ($totals === []) {
            return '—';
        }

        if (count($totals) > 1) {
            return count($totals).' currencies';
        }

        $currency = array_key_first($totals);

        return $currency.' '.$totals[$currency];
    }

    private function showsExecutiveExport(string $dashboardProfile): bool
    {
        return in_array($dashboardProfile, ['platform', 'company_admin', 'finance'], true);
    }

    private function canViewApprovalQueue(User $user, string $dashboardProfile): bool
    {
        return $dashboardProfile === 'company_admin'
            && $this->hasPermission($user, PermissionName::JobsApprove->value);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function dataEntryKpis(int $companyId, CarbonImmutable $today, CarbonImmutable $monthStart, User $user): array
    {
        $draftJobs = $this->jobQuery($companyId)->where('status', JobStatus::Draft->value)->count();
        $draftsUpdatedToday = $this->jobQuery($companyId)->where('status', JobStatus::Draft->value)->whereDate('updated_at', $today)->count();
        $planningReadyDrafts = $this->jobQuery($companyId)
            ->where('status', JobStatus::Draft->value)
            ->whereNotNull('planned_start_date')
            ->where(function (Builder $query): void {
                $query->whereNotNull('assigned_operator_id')
                    ->orWhereNotNull('assigned_operator_name');
            })
            ->whereNotNull('equipment_requirement')
            ->count();
        $jobsNeedingEntry = $this->jobsNeedingOperationalRecordCount($companyId);
        $returnedRecords = $this->returnedOperationalRecordsCount($companyId);
        $recentlyEnteredJobs = $this->jobQuery($companyId)
            ->where(function (Builder $query) use ($user): void {
                $query->where('created_by', $user->getKey())
                    ->orWhere('updated_by', $user->getKey());
            })
            ->where('updated_at', '>=', $monthStart)
            ->count();

        return [
            [
                'label' => 'Draft Jobs',
                'value' => (string) $draftJobs,
                'description' => $draftsUpdatedToday === 0 ? 'No Draft Jobs updated today' : "{$draftsUpdatedToday} updated today",
                'trend' => null,
                'status' => 'info',
                'icon' => 'clients',
            ],
            [
                'label' => 'Planning Ready',
                'value' => (string) $planningReadyDrafts,
                'description' => 'Draft Jobs ready for Operations handover',
                'trend' => null,
                'status' => 'success',
                'icon' => 'operations',
            ],
            [
                'label' => 'Needs Entry',
                'value' => (string) $jobsNeedingEntry,
                'description' => 'Jobs waiting for Job Card or Waybill entry',
                'trend' => null,
                'status' => 'warning',
                'icon' => 'approval',
            ],
            [
                'label' => 'Needs Correction',
                'value' => (string) $returnedRecords,
                'description' => 'Returned operational records to correct',
                'trend' => null,
                'status' => 'accent',
                'icon' => 'calendar',
            ],
            [
                'label' => 'Recently Entered',
                'value' => (string) $recentlyEnteredJobs,
                'description' => 'Jobs you created or updated this month',
                'trend' => null,
                'status' => 'info',
                'icon' => 'check',
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function operationsKpis(int $companyId, CarbonImmutable $today, CarbonImmutable $monthStart): array
    {
        $readyForDeployment = $this->jobQuery($companyId)->where('status', JobStatus::Scheduled->value)->count();
        $activeJobs = $this->jobQuery($companyId)
            ->whereIn('status', [JobStatus::Scheduled->value, JobStatus::InProgress->value, JobStatus::OnHold->value])
            ->count();
        $jobsToday = $this->jobQuery($companyId)->whereDate('planned_start_date', $today)->count();
        $overdueJobs = $this->jobQuery($companyId)
            ->whereIn('status', [JobStatus::Scheduled->value, JobStatus::InProgress->value, JobStatus::OnHold->value])
            ->whereDate('planned_end_date', '<', $today)
            ->count();
        $verifiedCards = JobCard::query()
            ->where('company_id', $companyId)
            ->where('approval_status', JobCardApprovalStatus::Verified->value)
            ->count();
        $completedThisMonth = $this->jobQuery($companyId)
            ->where('status', JobStatus::Completed->value)
            ->where('completed_at', '>=', $monthStart)
            ->count();

        return [
            [
                'label' => 'Ready for Deployment',
                'value' => (string) $readyForDeployment,
                'description' => 'Planning complete and awaiting field start',
                'trend' => null,
                'status' => 'info',
                'icon' => 'clients',
            ],
            [
                'label' => 'Active Jobs',
                'value' => (string) $activeJobs,
                'description' => 'Scheduled, live, or temporarily on hold',
                'trend' => null,
                'status' => 'success',
                'icon' => 'operations',
            ],
            [
                'label' => 'Jobs Today',
                'value' => (string) $jobsToday,
                'description' => 'Operational work due to start today',
                'trend' => null,
                'status' => 'accent',
                'icon' => 'calendar',
            ],
            [
                'label' => 'Overdue Jobs',
                'value' => (string) $overdueJobs,
                'description' => 'Past planned finish date and needs follow-up',
                'trend' => null,
                'status' => 'warning',
                'icon' => 'approval',
            ],
            [
                'label' => 'Accounts-Reviewed Job Cards',
                'value' => (string) $verifiedCards,
                'description' => 'Operational records reviewed by Accounts and ready for billing handoff',
                'trend' => null,
                'status' => 'info',
                'icon' => 'check',
            ],
            [
                'label' => 'Completed MTD',
                'value' => (string) $completedThisMonth,
                'description' => 'Jobs finished this month',
                'trend' => null,
                'status' => 'success',
                'icon' => 'revenue',
            ],
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    private function dataEntryAlerts(int $companyId): array
    {
        $alerts = [];

        $returnedCard = JobCard::query()
            ->where('company_id', $companyId)
            ->where('approval_status', JobCardApprovalStatus::Returned->value)
            ->latest('updated_at')
            ->first();

        if ($returnedCard instanceof JobCard) {
            $alerts[] = [
                'tone' => 'warning',
                'title' => 'Client Job Card correction required',
                'detail' => ($returnedCard->card_number ?: 'Client Job Card').' was returned to Operations for correction.',
                'url' => JobCardResource::getUrl('edit', ['record' => $returnedCard]),
            ];
        }

        $returnedWaybill = Waybill::query()
            ->where('company_id', $companyId)
            ->where('status', WaybillStatus::Returned->value)
            ->latest('updated_at')
            ->first();

        if ($returnedWaybill instanceof Waybill) {
            $alerts[] = [
                'tone' => 'info',
                'title' => 'Waybill correction required',
                'detail' => ($returnedWaybill->waybill_number ?: 'Waybill').' was returned for correction.',
                'url' => WaybillResource::getUrl('edit', ['record' => $returnedWaybill]),
            ];
        }

        $entryJob = $this->jobsNeedingOperationalRecordQuery($companyId)
            ->with('client')
            ->latest('updated_at')
            ->first();

        if ($entryJob instanceof Job) {
            $alerts[] = [
                'tone' => 'danger',
                'title' => 'Operational record entry required',
                'detail' => $entryJob->title.' needs its field document recorded in Atlas.',
                'url' => $this->jobUrl($entryJob),
            ];
        }

        return array_slice($alerts, 0, 3);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function dataEntryAttentionItems(int $companyId): array
    {
        $items = [];

        foreach ($this->jobQuery($companyId)
            ->with(['client'])
            ->where('status', JobStatus::Draft->value)
            ->latest('updated_at')
            ->limit(2)
            ->get() as $job) {
            $items[] = [
                'title' => $job->title,
                'description' => 'Review or update this Draft Job before handing it over to Operations.',
                'meta' => $job->client->display_name ?? $job->job_number,
                'status' => 'info',
                'action_label' => 'Edit Job',
                'url' => $this->jobUrl($job),
            ];
        }

        foreach ($this->jobsNeedingOperationalRecordQuery($companyId)
            ->with(['client'])
            ->latest('updated_at')
            ->limit(2)
            ->get() as $job) {
            $items[] = [
                'title' => $job->title,
                'description' => $job->isTrucking()
                    ? 'Record the returned field Waybill details in Atlas.'
                    : 'Record the client-issued Job Card details in Atlas.',
                'meta' => $job->client->display_name ?? $job->job_number,
                'status' => 'warning',
                'action_label' => $job->isTrucking() ? 'Record Waybill' : 'Record Job Card',
                'url' => $this->jobUrl($job),
            ];
        }

        foreach (JobCard::query()
            ->where('company_id', $companyId)
            ->where('approval_status', JobCardApprovalStatus::Returned->value)
            ->latest('updated_at')
            ->limit(2)
            ->get() as $jobCard) {
            $items[] = [
                'title' => $jobCard->card_number ?: 'Client Job Card',
                'description' => 'This Client Job Card was returned to Operations and needs correction before Accounts review can continue.',
                'meta' => $jobCard->job->title ?? 'Operational record',
                'status' => 'accent',
                'action_label' => 'Correct Job Card',
                'url' => JobCardResource::getUrl('edit', ['record' => $jobCard]),
            ];
        }

        foreach (Waybill::query()
            ->where('company_id', $companyId)
            ->where('status', WaybillStatus::Returned->value)
            ->latest('updated_at')
            ->limit(2)
            ->get() as $waybill) {
            $items[] = [
                'title' => $waybill->waybill_number ?: 'Waybill',
                'description' => 'This Waybill was returned and needs correction before verification can continue.',
                'meta' => $waybill->job->title ?? 'Operational record',
                'status' => 'accent',
                'action_label' => 'Correct Waybill',
                'url' => WaybillResource::getUrl('edit', ['record' => $waybill]),
            ];
        }

        return array_slice($items, 0, 6);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function operationsAttentionItems(int $companyId, CarbonImmutable $today): array
    {
        $items = [];

        foreach ($this->jobQuery($companyId)
            ->with(['client'])
            ->where('status', JobStatus::Scheduled->value)
            ->orderBy('planned_start_date')
            ->limit(2)
            ->get() as $job) {
            $items[] = [
                'title' => $job->title,
                'description' => 'Planning is complete and this Job is ready for deployment.',
                'meta' => $job->client->display_name ?? $job->job_number,
                'status' => 'info',
                'action_label' => 'Review Job',
                'url' => $this->jobUrl($job),
            ];
        }

        foreach ($this->jobQuery($companyId)
            ->with(['client'])
            ->where('status', JobStatus::InProgress->value)
            ->whereDate('planned_end_date', '<', $today)
            ->orderBy('planned_end_date')
            ->limit(2)
            ->get() as $job) {
            $items[] = [
                'title' => $job->title,
                'description' => 'This live Job is overdue and needs operational follow-up.',
                'meta' => $job->client->display_name ?? $job->job_number,
                'status' => 'warning',
                'action_label' => 'Review Job',
                'url' => $this->jobUrl($job),
            ];
        }

        foreach (JobCard::query()
            ->where('company_id', $companyId)
            ->where('approval_status', JobCardApprovalStatus::PendingVerification->value)
            ->latest('updated_at')
            ->limit(2)
            ->get() as $jobCard) {
            $items[] = [
                'title' => $jobCard->card_number ?: 'Client Job Card',
                'description' => 'Awaiting Accounts review before billing can continue.',
                'meta' => $jobCard->job->title ?? 'Operational record',
                'status' => 'accent',
                'action_label' => 'Open Job Card',
                'url' => JobCardResource::getUrl('view', ['record' => $jobCard]),
            ];
        }

        return array_slice($items, 0, 6);
    }

    /**
     * @return array<string, mixed>
     */
    private function dataEntryWorkItems(int $companyId): array
    {
        return [
            'heading' => 'Draft Jobs',
            'description' => 'Draft Jobs you can prepare or correct before handing them over to Operations.',
            'items' => $this->jobQuery($companyId)
                ->with(['client', 'site'])
                ->where('status', JobStatus::Draft->value)
                ->latest('updated_at')
                ->limit(5)
                ->get(),
            'mode' => 'recent',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function operationsWorkItems(int $companyId, CarbonImmutable $today): array
    {
        $upcoming = $this->jobQuery($companyId)
            ->with(['client', 'site'])
            ->whereIn('status', [JobStatus::Scheduled->value, JobStatus::InProgress->value, JobStatus::OnHold->value])
            ->whereNotNull('planned_start_date')
            ->whereDate('planned_start_date', '>=', $today)
            ->orderBy('planned_start_date')
            ->limit(5)
            ->get();

        return [
            'heading' => 'Upcoming Work',
            'description' => 'Deployment-ready and active operational jobs across the current company workspace.',
            'items' => $upcoming,
            'mode' => $upcoming->isNotEmpty() ? 'upcoming' : 'recent',
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    private function dataEntryQuickActions(bool $canCreateJobs, bool $canViewJobs, bool $canViewClients): array
    {
        $actions = [];

        if ($canCreateJobs) {
            $actions[] = [
                'title' => 'New Job',
                'description' => 'Capture a new internal Job record.',
                'icon' => 'operations',
                'url' => JobResource::getUrl('create'),
            ];
        }

        if ($canViewJobs) {
            $actions[] = [
                'title' => 'Jobs',
                'description' => 'Open Draft and recently entered Jobs.',
                'icon' => 'calendar',
                'url' => JobResource::getUrl('index'),
            ];
        }

        if ($canViewClients) {
            $actions[] = [
                'title' => 'Clients',
                'description' => 'Select clients and sites for new Jobs.',
                'icon' => 'clients',
                'url' => ClientResource::getUrl('index'),
            ];
        }

        return $actions;
    }

    private function jobsNeedingOperationalRecordCount(int $companyId): int
    {
        return $this->jobsNeedingOperationalRecordQuery($companyId)->count();
    }

    private function returnedOperationalRecordsCount(int $companyId): int
    {
        return JobCard::query()
            ->where('company_id', $companyId)
            ->where('approval_status', JobCardApprovalStatus::Returned->value)
            ->count()
            + Waybill::query()
                ->where('company_id', $companyId)
                ->where('status', WaybillStatus::Returned->value)
                ->count();
    }

    /**
     * @return Builder<Job>
     */
    private function jobsNeedingOperationalRecordQuery(int $companyId): Builder
    {
        return $this->jobQuery($companyId)
            ->where('status', JobStatus::InProgress->value)
            ->where(function (Builder $query): void {
                $query
                    ->where(function (Builder $heavyMachinery): void {
                        $heavyMachinery->where('job_type', JobType::HeavyMachinery->value)
                            ->whereDoesntHave('jobCards');
                    })
                    ->orWhere(function (Builder $trucking): void {
                        $trucking->where('job_type', JobType::Trucking->value)
                            ->whereDoesntHave('waybills');
                    });
            });
    }

    private function hasPermission(User $user, string $permission): bool
    {
        try {
            return $user->hasPermissionTo($permission);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }

    private function trendLabel(?int $current, ?int $previous): ?string
    {
        if ($current === null || $previous === null) {
            return null;
        }

        if ($current === 0 && $previous === 0) {
            return null;
        }

        if ($previous === 0) {
            return $current > 0 ? '+100%' : null;
        }

        $delta = (($current - $previous) / $previous) * 100;

        if (abs($delta) < 0.5) {
            return null;
        }

        $prefix = $delta > 0 ? '+' : '';

        return $prefix.number_format($delta, 1).'%';
    }
}
