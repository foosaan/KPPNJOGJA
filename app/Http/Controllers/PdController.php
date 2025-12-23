<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LayananPd;
use App\Models\Layanan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PdController extends Controller
{
    public function create()
    {
        // Ambil layanan PD dari tabel layanans
        $jenis_layanan = Layanan::where('layanan_type', 'PD')
            ->where('is_active', true)
            ->pluck('jenis_layanan')
            ->toArray();

        return view('user.layanan-pd.create', [
            'jenis_layanan' => $jenis_layanan,
            'userNip' => Auth::user()->nip,
            'noBerkasPreview' => 'PD-' . Carbon::now()->format('YmdHis')
        ]);
    }

public function store(Request $request)
{
    $request->validate([
        'id_satker' => 'required|string',
        'jenis_layanan' => 'required|string',
        'keterangan' => 'nullable|string',
        'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png',
    ]);

    // Generate no_berkas DULU sebelum upload file
    $today = Carbon::now()->format('Ymd');
    $jumlahHariIni = LayananPd::whereDate('created_at', Carbon::today())->count() + 1;
    $noBerkas = 'PD-' . $today . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

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

    LayananPd::create([
        'no_berkas'      => $noBerkas,
        'id_satker'      => Auth::user()->nip,
        'jenis_layanan'  => $request->jenis_layanan,
        'keterangan'     => $request->keterangan,
        'file_path'      => $filePath,
        'user_id'        => Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Layanan PD berhasil dikirim.');
    }
}
