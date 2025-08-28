<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{

    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // ===== Permission List =====
    $permissions = [
        // Admin permissions
        'manage_users',
        'manage_tps',
        'manage_reports',
        'manage_waste_scan',
        'manage_games',
        'view_dashboard',

        // Warga permissions
        'scan_waste',
        'report_illegal_tps',
        'view_tps_map',
        'play_games',
        'view_own_reports',

        // Guest (opsional, biasanya tanpa login)
        'view_public_tps_map',
    ];

    // Buat permission kalau belum ada
    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    // ===== Buat Role =====
    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $wargaRole = Role::firstOrCreate(['name' => 'warga']);

    // ===== Assign Permission ke Role =====
    $adminRole->syncPermissions([
        'manage_users',
        'manage_tps',
        'manage_reports',
        'manage_waste_scan',
        'manage_games',
        'view_dashboard',
    ]);

    $wargaRole->syncPermissions([
        'scan_waste',
        'report_illegal_tps',
        'view_tps_map',
        'play_games',
        'view_own_reports',
    ]);

    // Opsional: Guest role
    $guestRole = Role::firstOrCreate(['name' => 'guest']);
    $guestRole->syncPermissions(['view_public_tps_map']);

    // ===== Buat User contoh =====
    $admin = User::firstOrCreate(
        ['email' => 'admin@mail.test'],
        [
            'name' => 'Super Admin',
            'username' => 'super',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10), // <-- 2. TAMBAHKAN BARIS INI
        ]
    );
    $admin->assignRole($adminRole);

    $warga = User::firstOrCreate(
        ['email' => 'warga@mail.test'],
        [
            'name' => 'User Warga',
            'username' => 'warga',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10), // <-- 2. TAMBAHKAN BARIS INI

        ]
    );
    $warga->assignRole($wargaRole);
}
}
