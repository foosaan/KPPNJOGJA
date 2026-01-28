<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // ===== ADMIN/STAFF PROFILE =====
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $input = $request->validated();

        // Update password hanya jika diisi
        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        } else {
            unset($input['password']);
        }

        $user->fill($input);

        // Reset email verification jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Data Anda sudah terupdate.');
    }

    // ===== USER PROFILE =====
    public function editUserProfile(Request $request): View
    {
        // Get active divisions for sidebar
        $divisis = \App\Models\Divisi::where('is_active', true)->get();

        return view('user.profile.edit', [
            'user' => $request->user(),
            'divisis' => $divisis,
        ]);
    }

    public function updateUserProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $input = $request->validated();

        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        } else {
            unset($input['password']);
        }

        $user->fill($input);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('user.profile.edit')->with('success', 'Data Anda sudah terupdate.');
    }

    // ===== STAFF PROFILE =====
public function editStaffProfile(Request $request): View
{
    return view('staff.profile.edit', [
        'user' => $request->user(),
    ]);
}

public function updateStaffProfile(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();
    $input = $request->validated();

    // Update password hanya jika diisi
    if (!empty($input['password'])) {
        $input['password'] = bcrypt($input['password']);
    } else {
        unset($input['password']);
    }

    $user->fill($input);

    // Reset email verification jika email berubah
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    return Redirect::route('staff.profile.edit')->with('success', 'Data Anda sudah terupdate.');
}

    // ===== DELETE ACCOUNT =====
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
