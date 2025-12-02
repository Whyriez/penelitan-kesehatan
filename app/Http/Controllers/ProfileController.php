<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('pages.profile.index', [
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'institusi' => ['required', 'string', 'max:255'],
            // Tambahkan validasi alamat
            'alamat' => ['nullable', 'string', 'max:255'],
            'nomor_identitas' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'gelar_jabatan' => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
        ]);

        $user->update($validated);

        return redirect()->route('profile')
            ->with('success_profile', 'Informasi profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.'
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile')
            ->with('success_password', 'Kata sandi berhasil diubah.');
    }
}
