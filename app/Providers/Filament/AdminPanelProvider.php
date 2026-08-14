<?php

namespace App\Providers\Filament;

use App\Core\Administration\Filament\Pages\Dashboard;
use App\Core\Administration\Filament\Pages\ManageSettings;
use App\Core\Administration\Filament\Pages\SystemHealth;
use App\Core\Tenancy\Http\Middleware\ResolveTenant;
use Filament\Enums\ThemeMode;
use Filament\Enums\UserMenuPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile(isSimple: false)
            ->passwordReset()
            ->emailVerification()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->defaultThemeMode(ThemeMode::Light)
            ->brandLogo(fn () => new HtmlString(view('filament.brand.logo')->render()))
            ->brandLogoHeight('2.5rem')
            ->brandName('Atlas ERP')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('15.9375rem')
            ->collapsedSidebarWidth('4rem')
            ->collapsibleNavigationGroups(false)
            ->topbar()
            ->userMenu(true, UserMenuPosition::Topbar)
            ->globalSearchDebounce('400ms')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldKeyBindingSuffix()
            ->maxContentWidth(Width::Full)
            ->renderHook(PanelsRenderHook::TOPBAR_START, fn () => view('filament.hooks.topbar-sidebar-toggle'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_BEFORE, fn () => view('filament.hooks.topbar-company-switcher'))
            ->renderHook(PanelsRenderHook::SIDEBAR_FOOTER, fn () => view('filament.hooks.sidebar-footer-profile'))
            ->renderHook(PanelsRenderHook::SIDEBAR_NAV_START, fn () => view('filament.hooks.sidebar-navigation'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn () => view('filament.hooks.topbar-quick-create'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn () => view('filament.hooks.topbar-activity-trigger'))
            ->discoverResources(in: app_path('Core/Administration/Filament/Resources'), for: 'App\Core\Administration\Filament\Resources')
            ->discoverPages(in: app_path('Core/Administration/Filament/Pages'), for: 'App\Core\Administration\Filament\Pages')
            ->pages([
                Dashboard::class,
                ManageSettings::class,
                SystemHealth::class,
            ])
            ->discoverWidgets(in: app_path('Core/Administration/Filament/Widgets'), for: 'App\Core\Administration\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                ResolveTenant::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
