<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Bank;
use App\Models\Tps;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\User;
use App\Models\Report;
use App\Models\Surung;
use App\Filament\Admin\Resources\UserResource;
use App\Filament\Admin\Resources\TpsResource;
use App\Filament\Admin\Resources\SurungResource;
use App\Filament\Admin\Resources\BankResource;
use App\Filament\Admin\Resources\ReportResource;
use App\Models\RekeningBankSampahUser;
use App\Models\Store;

class UserStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Kartu Statistik #1: Total Users
            Stat::make('Total Pengguna', User::count())
                ->description('Jumlah semua pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->url(UserResource::getUrl()),

            Stat::make('Total TPS', Tps::count())
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

            Stat::make('Total Laporan TPS Liar', Report::count())
                ->description('Jumlah semua laporan yang diterima')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->url(ReportResource::getUrl()),

            Stat::make('Laporan Baru Hari Ini', Report::whereDate('created_at', today())->count())
                ->description('Laporan hari ini')
                ->color('warning')
                ->url(ReportResource::getUrl()),
            // Stat::make('Total Toko Daur Ulang', Store::count())
            //     ->description('Jumlah semua Toko Daur Ulang terdaftar')
            //     ->color('success'),
            // Stat::make('Total Rekening Bank Sampah ', RekeningBankSampahUser::count())
            //     ->description('Jumlah semua rekening bank sampah terdaftar')
            //     ->color('success'),
        ];
    }
}
