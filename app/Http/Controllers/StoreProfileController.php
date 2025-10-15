<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreProfileController extends Controller
{
    public function redirectToMyStore()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->route('store.profile.create')
                ->with('info', 'Anda harus membuat profil toko terlebih dahulu.');
        }
        
        return redirect()->route('marketplace.store.show', $store);
    }

    public function show(Store $store)
    {
        return view('pages.marketplace.profile-show', compact('store'));
    }

    public function create()
    {
        $store = new Store();
        return view('pages.marketplace.profile-edit', compact('store'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:stores,name',
            'operational_days' => 'required|array|min:1',
            'address' => 'required|string',
            'district' => 'required|string|max:255',
            'sub_district' => 'required|string|max:255',
            'opening_hour' => 'required|date_format:H:i',
            'closing_hour' => 'required|date_format:H:i',
            'phone_number' => 'nullable|string|max:15',
            'description' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'image_path' => 'nullable|image|max:10240', 
        ]);
        
        $validatedData['user_id'] = Auth::id();
        $validatedData['is_active'] = true;
        $validatedData['slug'] = Str::slug($validatedData['name']);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('stores', 'public');
            $validatedData['image_path'] = $path;
        }

        // [PERBAIKAN 1] Simpan hasil create ke variabel $newStore
        $newStore = Store::create($validatedData);

        // [PERBAIKAN 2] Berikan variabel $newStore ke dalam route()
        return redirect()->route('store.profile.show', $newStore)->with('success', 'Profil Toko berhasil dibuat!');
    }

    public function edit(Store $store)
    {
        if (Auth::id() !== $store->user_id) {
            abort(403, 'AKSES DITOLAK');
        }

        return view('pages.marketplace.profile-edit', compact('store'));
    }

    public function update(Request $request)
    {
        $store = Store::where('user_id', Auth::id())->firstOrFail();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:stores,name,' . $store->id,
            'operational_days' => 'required|array|min:1',
            'address' => 'required|string',
            'district' => 'required|string|max:255',
            'sub_district' => 'required|string|max:255',
            'opening_hour' => 'required|date_format:H:i',
            'closing_hour' => 'required|date_format:H:i',
            'phone_number' => 'nullable|string|max:15',
            'description' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'image_path' => 'nullable|image|max:10240',
            'status' => 'required|boolean',
        ]);
        
        $validatedData['slug'] = Str::slug($validatedData['name']);
        $validatedData['is_active'] = $validatedData['status'];
        unset($validatedData['status']);
        
        if ($request->hasFile('image_path')) {
            if ($store->image_path) {
                Storage::delete('public/' . $store->image_path);
            }
            $path = $request->file('image_path')->store('stores', 'public');
            $validatedData['image_path'] = $path;
        }
        $store->update($validatedData);
        return redirect()->route('store.profile.show', $store)->with('success', 'Profil Toko berhasil diperbarui!');
    }
}