<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Layanan;
use App\Models\Divisi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BankController extends Controller
{
    public function create()
    {
        // Get divisi Bank
        $divisi = Divisi::where('nama', 'Bank')->first();
        
        $jenis_layanan = [];
        if ($divisi) {
            $jenis_layanan = Layanan::where('divisi_id', $divisi->id)
                ->where('is_active', true)
                ->pluck('jenis_layanan')
                ->toArray();
        }

        return view('user.layanan-bank.create', [
            'jenis_layanan' => $jenis_layanan,
            'userNip' => Auth::user()->nip,
            'noBerkasPreview' => 'BANK-' . Carbon::now()->format('YmdHis')
        ]);
    }

public function store(Request $request)
{
    $request->validate([
        'id_satker' => 'required|string',
        'jenis_layanan' => 'required|string|exists:layanans,jenis_layanan',
        'keterangan' => 'required|string',
        'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar',
    ], [
        'file_upload.mimes' => 'Format file harus PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP, atau RAR',
    ]);

    // ==============================
    // 1. Generate no_berkas DULU
    // ==============================
    $today = Carbon::now()->format('Ymd');
    $jumlahHariIni = Bank::whereDate('created_at', Carbon::today())->count() + 1;
    $noBerkas = 'BANK-' . $today . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

    // ==============================
    // 2. Ambil file
    // ==============================
    $file = $request->file('file_upload');

    // Nama file asli tanpa ekstensi
    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

    // Bersihkan nama file
    $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);

    // Ekstensi
    $extension = $file->getClientOriginalExtension();

    // ==============================
    // 3. Nama file custom
    // ==============================
    $newFileName = $noBerkas . '-' . $cleanName . '.' . $extension;

    // ==============================
    // 4. Simpan file
    // ==============================
    $filePath = $file->storeAs('uploads/layanan', $newFileName, 'public');

    // ==============================
    // 5. Simpan ke database
    // ==============================
    Bank::create([
        'no_berkas' => $noBerkas,
        'id_satker' => Auth::user()->nip,
        'jenis_layanan' => $request->jenis_layanan,
        'keterangan' => $request->keterangan,
        'file_path' => $filePath,
        'user_id' => Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Layanan Bank berhasil dikirim.');
 }

}
