<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User; 

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil.
     */
    public function edit()
    {
        $user = Auth::user(); 
        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui data profil.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */ // <-- PENAMBAHAN PETUNJUK UNTUK EDITOR
        $user = Auth::user(); 

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $validatedData['profile_photo_path'] = $path;
        }

        $user->update($validatedData);

        return redirect()->route('profile.edit')->with('status', 'Profil berhasil diperbarui!');
    }
}
