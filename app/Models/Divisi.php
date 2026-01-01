<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Divisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($divisi) {
            if (empty($divisi->slug)) {
                $divisi->slug = Str::slug($divisi->nama);
            }
        });

        static::updating(function ($divisi) {
            if ($divisi->isDirty('nama') && empty($divisi->slug)) {
                $divisi->slug = Str::slug($divisi->nama);
            }
        });
    }

    /**
     * Get divisi by slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Get all layanan for this divisi
     */
    public function layanans()
    {
        return $this->hasMany(Layanan::class);
    }

    /**
     * Scope for active divisi
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
