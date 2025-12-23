@extends('admin.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Tambah Akun User</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
    </div>

    <div class="mb-3">
        <label>NIP</label>
        <input type="text" name="nip" class="form-control" required value="{{ old('nip') }}">
    </div>

    <div class="mb-3">
        <label>Nama Satuan Kerja <small>(boleh kosong)</small></label>
        <input type="text" name="nama_satker" class="form-control" value="{{ old('nama_satker') }}">
    </div>

    <div class="mb-3">
        <label>Email </label>
        <input type="email" name="email" class="form-control"required value="{{ old('email') }}">
    </div>

    <div class="mb-3">
        <label>No HP <small>(boleh kosong)</small></label>
        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
    </div>

    <div class="mb-3">
        <label>Jabatan <small>(boleh kosong)</small></label>
        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}">
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
    </div>

    <div class="mb-3">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection
