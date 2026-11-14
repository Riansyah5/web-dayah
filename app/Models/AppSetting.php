<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Ambil nilai setting berdasarkan key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Simpan atau update nilai setting
     */
    public static function set(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? ($value ? '1' : '0') : $value]
        );

        Cache::forget("app_setting_{$key}");
    }

    /**
     * Cek apakah aplikasi sedang dalam mode pemeliharaan (maintenance)
     */
    public static function isMaintenance(): bool
    {
        return (bool) self::get('maintenance_mode', false);
    }

    /**
     * Ambil data lengkap informasi pemeliharaan
     */
    public static function getMaintenanceData(): array
    {
        return [
            'is_active' => self::isMaintenance(),
            'title' => self::get('maintenance_title', 'Sistem Sedang Dalam Pemeliharaan'),
            'message' => self::get(
                'maintenance_message',
                'Saat ini aplikasi Dayah Mataqu sedang dalam proses pemeliharaan rutin atau peningkatan sistem. Mohon menunggu beberapa saat hingga sistem kembali aktif.'
            ),
            'end_at' => self::get('maintenance_end_at'),
            'contact_phone' => self::get('maintenance_contact_phone'),
        ];
    }
}
