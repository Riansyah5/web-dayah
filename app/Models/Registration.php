<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    protected $casts = [
        'birth_date' => 'date',
        'payment_date' => 'date',
        'payment_amount' => 'integer',
        'selection_score' => 'float',
    ];

    /**
     * URL Bukti Slip Pembayaran
     */
    public function getPaymentReceiptUrlAttribute(): ?string
    {
        if ($this->payment_receipt) {
            return asset('storage/' . $this->payment_receipt);
        }
        return null;
    }

    /**
     * Label Status Pembayaran
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'verified' => 'Pembayaran Sah',
            'rejected' => 'Pembayaran Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }

    /**
     * Badge Status Pembayaran
     */
    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'verified' => '<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="ti ti-check me-1"></i>Lunas</span>',
            'rejected' => '<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="ti ti-x me-1"></i>Tidak Valid</span>',
            default => '<span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="ti ti-clock me-1"></i>Perlu Dicek</span>',
        };
    }

    /**
     * Relasi ke Tahun Ajaran tujuan pendaftaran
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relasi ke Santri (jika sudah berstatus 'enrolled' / diterima resmi)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Label Status Pendaftaran
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Berkas Terverifikasi',
            'passed' => 'Lulus Seleksi',
            'rejected' => 'Tidak Lulus',
            'enrolled' => 'Santri Aktif',
            default => ucfirst($this->status),
        };
    }

    /**
     * Badge HTML Status
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning text-dark"><i class="ti ti-clock me-1"></i>Menunggu Verifikasi</span>',
            'verified' => '<span class="badge bg-info text-white"><i class="ti ti-file-check me-1"></i>Terverifikasi</span>',
            'passed' => '<span class="badge bg-primary text-white"><i class="ti ti-circle-check me-1"></i>Lulus Seleksi</span>',
            'rejected' => '<span class="badge bg-danger text-white"><i class="ti ti-circle-x me-1"></i>Tidak Lulus</span>',
            'enrolled' => '<span class="badge bg-success text-white"><i class="ti ti-school me-1"></i>Santri Aktif</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    /**
     * Helper pembuatan Nomor Pendaftaran Unik otomatis
     * Format: PSB-YYYYMM-XXXX
     */
    public static function generateRegistrationNumber(): string
    {
        $prefix = 'PSB-' . date('Y');
        $last = self::where('registration_number', 'LIKE', $prefix . '%')
            ->orderBy('registration_number', 'desc')
            ->first();

        if ($last) {
            $parts = explode('-', $last->registration_number);
            $seq = isset($parts[2]) ? (int) $parts[2] + 1 : 1;
        } else {
            $seq = 1;
        }

        return $prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}

