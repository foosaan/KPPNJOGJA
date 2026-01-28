<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasLayanan extends Model
{
    use HasFactory;

    protected $table = 'berkas_layanans';

    // Kolom-kolom yang boleh diisi secara massal (Mass Assignment)
    protected $fillable = [
        'no_berkas',        // Nomor unik berkas
        'divisi_id',        // ID Divisi tujuan
        'id_satker',        // NIP Pengirim
        'jenis_layanan',    // Nama layanan
        'keterangan',       // Catatan tambahan dari user
        'file_path',        // Lokasi file yang diupload
        'original_filename',// Nama asli file
        'user_id',          // ID User pengirim (Foreign Key)
        'staff_id',         // ID Staff yang memproses (Foreign Key - Nullable)
        'status',           // Status: baru, diproses, selesai, ditolak
        'feedback',         // Catatan balik dari staff
        'feedback_file',    // File balasan dari staff
    ];

    /**
     * Relasi ke Model Divisi
     * (Setiap Berkas milik satu Divisi)
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    /**
     * Relasi ke Model User (Pengirim)
     * (Setiap Berkas dikirim oleh satu User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Model User (Staff)
     * (Setiap Berkas diproses oleh satu Staff tertentu, bisa null jika belum diproses)
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
