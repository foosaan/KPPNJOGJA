@extends('layouts.admin.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data User</h1>
</div>

<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            + Tambah Data
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th style="text-align: center;">Nama</th>
                            <th style="text-align: center;">NIP</th>
                            <th style="text-align: center;">Email</th>
                            <th style="text-align: center;">No HP</th>
                            <th style="text-align: center;">Jabatan</th>
                            <th style="text-align: center;">Nama Satker</th>
                            <th style="text-align: center;">Tanggal Dibuat</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td style="text-align: center;">{{ $index + 1 }}</td>
                                <td style="text-align: center;">{{ $user->name }}</td>
                                <td style="text-align: center;">{{ $user->nip }}</td>
                                <td style="text-align: center;">{{ $user->email }}</td>
                                <td style="text-align: center;">{{ $user->no_hp }}</td>
                                <td style="text-align: center;">{{ $user->jabatan }}</td>
                                <td style="text-align: center;">{{ $user->nama_satker ?? '-' }}</td>
                                <td style="text-align: center;">{{ $user->created_at->format('d-m-Y') }}</td>
                                <td style="text-align: center;">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning"style="background-color: #ffc107; color: #212529; border-radius: 4px; padding: 6px 12px; margin-right: 5px; text-decoration: none; font-size: 12px;">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 30px;">
                                    Belum ada data user
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <small class="text-muted"><strong>Total User:</strong> {{ $users->count() }}</small>
    </div>
</div>
@endsection
