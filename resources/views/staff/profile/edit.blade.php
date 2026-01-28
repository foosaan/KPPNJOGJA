@extends('layouts.staff.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Profile</h1>
</div>

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Profile Information</h5>
            <p class="text-muted">Update your account's profile information, email, NIP, or password (optional).</p>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('staff.profile.update') }}">
                @csrf
                @method('patch')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">NIP</label>
                    <input name="nip" type="text" class="form-control" value="{{ old('nip', $user->nip) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-muted">
                                Your email is unverified.
                                <button form="send-verification" class="btn btn-link p-0">Click here to resend verification</button>
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Password fields (optional) -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password (optional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave empty if not changing">
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Leave empty if not changing">
                    @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary ms-2">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
