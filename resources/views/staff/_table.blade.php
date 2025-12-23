<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No Berkas</th>
                <th>Nama User</th>
                <th>Jenis Layanan</th>
                <th>Keterangan</th>
                <th>File</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Alasan Penolakan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $request)
                @if($request->status == 'ditolak')
                <tr>
                    <td>{{ $request->no_berkas }}</td>
                    <td>{{ $request->user->name ?? '-' }}</td>
                    <td>{{ $request->jenis_layanan }}</td>
                    <td>{{ $request->keterangan ?? '-' }}</td>
                    <td>
                        @if($request->file_path)
                            <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                        @else - @endif
                    </td>
                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($request->status ?? '-') }}</td>
                    <td>{{ $request->alasan_penolakan ?? '-' }}</td>
                    <td>
                        <form action="{{ route('staff.updateStatus', [$request->id, $jenis]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-control form-control-sm" onchange="toggleAlasan(this)">
                                <option value="baru">Baru</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                                <option value="ditolak" selected>Ditolak</option>
                            </select>
                            <textarea name="alasan_penolakan" class="form-control mt-2 alasan-box"
                                style="display:block;" required
                                placeholder="Tuliskan alasan penolakan...">{{ $request->alasan_penolakan }}</textarea>
                            <button type="submit" class="btn btn-sm btn-primary mt-2">Simpan</button>
                        </form>
                    </td>
                </tr>
                @endif
            @empty
                <tr><td colspan="9" class="text-center">Tidak ada data layanan Vera{{ strtoupper($jenis) }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
