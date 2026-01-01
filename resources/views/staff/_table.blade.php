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
                <th>Feedback</th>
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
                        @if($request->feedback)
                            <div><strong>{{ $request->feedback }}</strong></div>
                            @if($request->feedback_file)
                                <a href="{{ asset('storage/' . $request->feedback_file) }}" target="_blank">
                                    Lihat File Feedback
                                </a>
                            @endif
                        @else
                            -
                        @endif
                    </td>
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

                        {{-- Form feedback untuk berkas ditolak --}}
                        @if(!$request->feedback)
                            <form action="{{ route('staff.feedback.update', $request->id) }}" method="POST" enctype="multipart/form-data" class="mt-2 border-top pt-2">
                                @csrf
                                @method('PUT')
                                <textarea name="feedback" class="form-control form-control-sm" placeholder="Isi feedback..." required></textarea>
                                <input type="file" name="feedback_file" class="form-control form-control-sm mt-1">
                                <button type="submit" class="btn btn-success btn-sm mt-1">Simpan Feedback</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endif
            @empty
                <tr><td colspan="10" class="text-center">Tidak ada data layanan {{ strtoupper($jenis) }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

