<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vera;
use App\Models\Layanan;
use App\Models\Divisi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VeraController extends Controller
{
    public function create()
    {
        // Get divisi Vera
        $divisi = Divisi::where('nama', 'Vera')->first();
        
        $jenis_layanan = [];
        if ($divisi) {
            $jenis_layanan = Layanan::where('divisi_id', $divisi->id)
                ->where('is_active', true)
                ->pluck('jenis_layanan')
                ->toArray();
        }

        return view('user.layanan-vera.create', [
            'jenis_layanan' => $jenis_layanan,
            'userNip' => Auth::user()->nip,
            'noBerkasPreview' => 'Vera-' . Carbon::now()->format('YmdHis')
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'id_satker' => 'required|string',
        'jenis_layanan' => 'required|string',
        'keterangan' => 'nullable|string',
        'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar',
        ], [
        'file_upload.mimes' => 'Format file harus PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP, atau RAR',
    ]);

    // Generate no_berkas DULU sebelum upload file
    $today = Carbon::now()->format('Ymd');
    $jumlahHariIni = Vera::whereDate('created_at', Carbon::today())->count() + 1;
    $noBerkas = 'VERA-' . $today . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

    // Ambil file yang diupload
    $file = $request->file('file_upload');
    
    // Ambil nama file asli (tanpa ekstensi)
    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    
    // Bersihkan nama file dari karakter aneh (spasi jadi underscore, hapus karakter spesial)
    $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
    
    // Ambil ekstensi file asli
    $extension = $file->getClientOriginalExtension();
    
    // Buat nama file baru: VERA-NomorBerkas-NamaFileAsli.extension
    // Contoh: VERA-20250620-001-LaporanKeuangan.pdf
    $newFileName = $noBerkas . '-' . $cleanName . '.' . $extension;
    
    // Simpan file dengan nama custom
    $filePath = $file->storeAs('uploads/layanan', $newFileName, 'public');

    Vera::create([
        'no_berkas'      => $noBerkas,
        'id_satker'      => Auth::user()->nip,
        'jenis_layanan'  => $request->jenis_layanan,
        'keterangan'     => $request->keterangan,
        'file_path'      => $filePath,
        'user_id'        => Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Layanan VERA berhasil dikirim.');
    }
}
