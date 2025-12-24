<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Umum;
use App\Models\Layanan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UmumController extends Controller
{
    public function create()
    {

        $jenis_layanan = Layanan::where('layanan_type', 'Umum')
            ->where('is_active', true)
            ->pluck('jenis_layanan')
            ->toArray();

        return view('user.layanan-umum.create', [
            'jenis_layanan' => $jenis_layanan,
            'userNip' => Auth::user()->nip,
            'noBerkasPreview' => 'UMUM-' . Carbon::now()->format('YmdHis')
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

        // Upload file
        $file = $request->file('file_upload');
        $filePath = $file->store('uploads/layanan', 'public');
        $originalFilename = $file->getClientOriginalName(); // Ambil nama file asli

        // Generate nomor berkas unik
        $today = Carbon::now()->format('Ymd');
        $jumlahHariIni = Umum::whereDate('created_at', Carbon::today())->count() + 1;
        $noBerkas = 'UMUM-' . $today . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

        Umum::create([
            'no_berkas' => $noBerkas,
            'id_satker' => Auth::user()->nip,
            'jenis_layanan' => $request->jenis_layanan,
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'original_filename' => $originalFilename, // Simpan nama file asli
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Layanan Umum berhasil dikirim.');
    }
}