<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SidebarSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $menus = [
            ['menu_key' => 'menu_kbm', 'label' => 'Menu KBM'],
            ['menu_key' => 'menu_santri', 'label' => 'Menu Santri'],
            ['menu_key' => 'menu_akademik', 'label' => 'Menu Akademik'],
            ['menu_key' => 'menu_tahfizh', 'label' => 'Menu Tahfizh'],
            ['menu_key' => 'menu_pengasuhan', 'label' => 'Menu Pengasuhan'],
            ['menu_key' => 'menu_kbm_admin', 'label' => 'Menu KBM Admin'],
            ['menu_key' => 'menu_tahfizh_admin', 'label' => 'Menu Tahfizh Admin'],
            ['menu_key' => 'menu_akademik_admin', 'label' => 'Menu Akademik Admin'],
            ['menu_key' => 'menu_pegawai_admin', 'label' => 'Menu Pegawai Admin'],
            ['menu_key' => 'menu_pengasuhan_admin', 'label' => 'Menu Pengasuhan Admin'],

        ];

        foreach ($menus as $menu) {
            \App\Models\SidebarSetting::updateOrCreate(['menu_key' => $menu['menu_key']], $menu);
        }
    }
}
