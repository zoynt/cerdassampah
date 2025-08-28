<?php

namespace App\Providers\Filament;

use Closure;
use Filament\Pages;
use Filament\Panel;
use App\Models\User;
use Filament\Widgets;
use Filament\PanelProvider;
use Illuminate\Http\Request;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckAdminRole;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Admin\Widgets\UserStatsOverview;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{

    
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Lime,   
            ])
            ->brandLogo(fn () => new HtmlString('
            <div class="flex items-center gap-x-2">
                <img src="' . asset('img/logobiasa.png') . '" class="h-8">
                <span class="text-lg font-bold tracking-tight text-gray-950 dark:text-white">
                    CerdasSampah
                </span>
            </div>
            '))
            ->darkModeBrandLogo(fn () => new HtmlString('
            <div class="flex items-center gap-x-2">
                <img src="' . asset('img/logoputih.png') . '" class="h-8">
                <span class="text-lg font-bold tracking-tight text-gray-950 dark:text-white">
                    CerdasSampah
                </span>
            </div>
            '))
            // ->brandLogoHeight('2rem')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                UserStatsOverview::class,
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->middleware([
                CheckAdminRole::class,
            ]);
    }
}
