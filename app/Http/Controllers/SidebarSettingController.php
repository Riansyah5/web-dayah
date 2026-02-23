<?php

namespace App\Http\Controllers;

use App\Models\SidebarSetting;
use Illuminate\Http\Request;

class SidebarSettingController extends Controller
{
    public function index()
    {
        $settings = SidebarSetting::all();
        return view('admin.sidebar_settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->settings as $id => $value) {
            SidebarSetting::where('id', $id)->update(['is_active' => $value]);
        }
        return back()->with('success', 'Pengaturan sidebar berhasil diperbarui!');
    }
}