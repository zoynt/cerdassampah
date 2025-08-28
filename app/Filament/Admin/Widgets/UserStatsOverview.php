<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Bank;
use App\Models\Tps;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\User; // <-- Jangan lupa import model User
use App\Models\Report; // <-- Contoh import model lain jika diperlukan
use App\Models\Surung;
use App\Filament\Admin\Resources\UserResource; // <-- Jangan lupa import resource User
use App\Filament\Admin\Resources\TpsResource; // <-- Jangan lupa import resource User
use App\Filament\Admin\Resources\SurungResource; // <-- Jangan lupa import resource User
use App\Filament\Admin\Resources\BankResource; // <-- Jangan lupa import resource User
use App\Filament\Admin\Resources\ReportResource; // <-- Jangan lupa import resource User


class UserStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Kartu Statistik #1: Total Users
            Stat::make('Total Users', User::count())
                ->description('Jumlah semua user terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->url(UserResource::getUrl()),

            Stat::make('Total Tps', Tps::count())
                ->description('Jumlah semua TPS terdaftar')
                ->color('success')
                ->url(TpsResource::getUrl()),
            Stat::make('Total Surung Sintak', Surung::count())
                ->description('Jumlah semua Surung terdaftar')
                ->url(SurungResource::getUrl())
                ->color('success'),
            Stat::make('Total Bank Sampah', Bank::count())
                ->description('Jumlah semua Bank Sampah terdaftar')
                ->url(BankResource::getUrl())
                ->color('success'),

            Stat::make('Total Reports', Report::count())
                ->description('Jumlah semua laporan yang diterima')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->url(ReportResource::getUrl()),

            Stat::make('Laporan Baru Hari Ini', Report::whereDate('created_at', today())->count())
                ->description('Laporan hari ini')
                ->color('warning')
                ->url(ReportResource::getUrl()),

        ];
    }
}
