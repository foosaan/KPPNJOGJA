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
        'layanan_type',
        'jenis_layanan',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    // Filter berdasarkan type
    public function scopeByType($query, $type)
    {
        return $type ? $query->where('layanan_type', $type) : $query;
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

    // Badge warna untuk UI
    public function getBadgeColorAttribute()
    {
        return [
            'Vera' => 'info',
            'PD' => 'warning',
            'MSKI' => 'success',
            'Bank' => 'primary',
        ][$this->layanan_type] ?? 'secondary';
    }

    // Icon otomatis berdasarkan type
    public function getIconAttribute()
    {
        return [
            'Vera' => 'fa-check-circle',
            'PD' => 'fa-money-bill-wave',
            'MSKI' => 'fa-chart-line',
            'Bank' => 'fa-university',
        ][$this->layanan_type] ?? 'fa-cog';
    }
}
