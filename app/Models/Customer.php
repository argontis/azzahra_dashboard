<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'costomer';

    protected $primaryKey = 'id_costomer';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_costomer',
        'cos_nama',
        'username',
        'password',
        'cos_alamat',
        'cos_hp',
        'cos_cabang',
        'cos_device',
        'cos_tipe',
        'cos_model',
        'cos_no_seri',
        'cos_asesoris',
        'cos_status',
        'cos_pswd',
        'cos_pswd_type',
        'cos_pswd_canvas',
        'cos_keluhan',
        'cos_keterangan',
        'cos_tgl_lahir',
        'cos_tanggal',
        'cos_jam',
        'cos_poin',
        'cos_score',
        'cos_tier',
        'total_transaksi',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'cos_kode', 'id_costomer');
    }

    /**
     * Hitung ulang skor dan level tier customer secara dinamis berbasis data transaksi nyata.
     */
    public function recalculateScore(): array
    {
        $count = $this->transaksi()->count();

        // Skoring dinamis:
        // 0-1 tx: Skor 1 (Reguler)
        // 2 tx: Skor 2 (Reguler+)
        // 3-4 tx: Skor 3-4 (Loyal)
        // >= 5 tx: Skor 5 (Prioritas / VIP)
        $score = match (true) {
            $count >= 5 => 5,
            $count === 4 => 4,
            $count === 3 => 3,
            $count === 2 => 2,
            default => 1,
        };

        $tier = match (true) {
            $score === 5 => 'prioritas',
            $score >= 3 => 'loyal',
            default => 'reguler',
        };

        $this->update([
            'cos_score' => $score,
            'cos_tier' => $tier,
            'total_transaksi' => $count,
        ]);

        return [
            'score' => $score,
            'tier' => $tier,
            'total_transaksi' => $count,
        ];
    }

    /**
     * Mengecek apakah customer adalah Pelanggan Prioritas (VIP).
     */
    public function getIsPriorityAttribute(): bool
    {
        return ($this->cos_tier === 'prioritas') || ($this->cos_score >= 5) || ($this->total_transaksi >= 5);
    }

    /**
     * Label representasi tier customer.
     */
    public function getTierLabelAttribute(): string
    {
        return match ($this->cos_tier) {
            'prioritas' => 'Prioritas (VIP)',
            'loyal' => 'Loyal',
            default => 'Reguler',
        };
    }

    /**
     * Batas maksimal persentase diskon yang berhak didapatkan.
     */
    public function getMaxDiscountPercentAttribute(): int
    {
        return match ($this->cos_tier) {
            'prioritas' => 50, // s/d 50% untuk Prioritas
            'loyal' => 15,     // s/d 15% untuk Loyal
            default => 0,      // Reguler
        };
    }
}
