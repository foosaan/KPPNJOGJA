<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanans';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'divisi_id',
        'jenis_layanan',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the divisi that owns the layanan
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    // Filter berdasarkan divisi
    public function scopeByDivisi($query, $divisiId)
    {
        return $divisiId ? $query->where('divisi_id', $divisiId) : $query;
    }

    // Filter layanan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Filter layanan nonaktif
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Quick search layanan
    public function scopeSearch($query, $keyword)
    {
        return $keyword
            ? $query->where('jenis_layanan', 'like', "%{$keyword}%")
            : $query;
    }

    // Filter berdasarkan status
    public function scopeStatus($query, $status)
    {
        if ($status === '1')
            return $query->where('is_active', true);
        if ($status === '0')
            return $query->where('is_active', false);

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // Badge warna untuk UI (based on divisi name)
    public function getBadgeColorAttribute()
    {
        if (!$this->divisi) return 'secondary';
        
        return [
            'Vera' => 'info',
            'PD' => 'warning',
            'MSKI' => 'success',
            'Bank' => 'primary',
            'Umum' => 'dark',
        ][$this->divisi->nama] ?? 'secondary';
    }

    // Icon otomatis berdasarkan divisi
    public function getIconAttribute()
    {
        if (!$this->divisi) return 'fa-cog';
        
        return [
            'Vera' => 'fa-check-circle',
            'PD' => 'fa-money-bill-wave',
            'MSKI' => 'fa-chart-line',
            'Bank' => 'fa-university',
            'Umum' => 'fa-folder',
        ][$this->divisi->nama] ?? 'fa-cog';
    }
}
