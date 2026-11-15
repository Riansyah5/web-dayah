<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permission kelola-psb jika belum ada
        $permission = Permission::firstOrCreate([
            'name' => 'kelola-psb',
            'guard_name' => 'web',
        ]);

        // Berikan ke Superadmin
        $superadmin = Role::where('name', 'Superadmin')->first();
        if ($superadmin && !$superadmin->hasPermissionTo($permission)) {
            $superadmin->givePermissionTo($permission);
        }

        // Berikan ke Admin
        $admin = Role::where('name', 'Admin')->first();
        if ($admin && !$admin->hasPermissionTo($permission)) {
            $admin->givePermissionTo($permission);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::where('name', 'kelola-psb')->first();
        if ($permission) {
            $permission->delete();
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
