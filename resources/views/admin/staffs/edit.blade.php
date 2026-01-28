@extends('layouts.admin.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Staff</h1>
    </div>

    <div class="container-fluid mt-5">

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Pesan error validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.staffs.update', $staff->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control"
                       required value="{{ old('name', $staff->name) }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       required value="{{ old('email', $staff->email) }}">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">NIP</label>
                <input type="text" name="nip" class="form-control"
                       value="{{ old('nip', $staff->nip) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Divisi</label>
                <select name="divisi" class="form-select" required>
                    <option value="" disabled>-- Pilih Divisi --</option>
                    @foreach ($divisiList as $divisi)
                        <option value="{{ $divisi }}" {{ old('divisi', $staff->divisi) == $divisi ? 'selected' : '' }}>
                            {{ $divisi }}
                        </option>
                    @endforeach
                </select>
                @error('divisi') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password baru (opsional)</label>
                <input type="password" name="password" class="form-control"
                       autocomplete="new-password" placeholder="Password baru (opsional)">
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                       autocomplete="new-password" placeholder="Konfirmasi Password">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
