<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananPd extends Model
{
    use HasFactory;

    protected $table = 'pds';

    protected $fillable = [
        'no_berkas',
        'id_satker',
        'jenis_layanan',
        'keterangan',
        'file_path',
        'status',
        'staff_id',
        'alasan_penolakan',
        'feedback',
        'feedback_file'
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_satker', 'nip');
    }
}
