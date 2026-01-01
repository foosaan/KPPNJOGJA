<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasLayanan extends Model
{
    use HasFactory;

    protected $table = 'berkas_layanans';

    protected $fillable = [
        'no_berkas',
        'divisi_id',
        'id_satker',
        'jenis_layanan',
        'keterangan',
        'file_path',
        'original_filename',
        'user_id',
        'staff_id',
        'status',
        'feedback',
        'feedback_file',
    ];

    /**
     * Get the divisi
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    /**
     * Get the user who submitted
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the staff who processed
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
