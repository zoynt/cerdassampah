@extends('layouts.dashboard')

@section('title', 'Profil')

@section('content')

    <div class="relative bg-gradient-to-b from-green-700 via-green-700 to-green-500 -mt-4 sm:-mt-6 text-white mb-6">

        <div class="relative z-10 p-8 pt-10 pb-20 flex flex-col justify-center items-center text-center">


            <div class="flex items-center justify-center w-8 h-8 mb-4 rounded-full overflow-hidden">
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight text-white">
                Edit Profil
            </h1>
            <div class="flex items-center justify-center w-8 h-8 mb-4 rounded-full overflow-hidden">
            </div>

        </div>
        <div class="absolute bottom-[-1px] left-0 w-full text-slate-50">
            <svg viewBox="0 0 1440 120" fill="currentColor" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M1440,120H0V20.48c0,0,202.4,69.52,480,69.52s480-139.04,960-69.52V120Z"></path>
            </svg>
        </div>
    </div>

    <div class="p-4 sm:p-6 lg:p-8 -mt-16 relative z-10">
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div x-data="{ photoName: null, photoPreview: null }"
                    class="col-span-1 md:col-span-2 flex flex-col items-center text-center space-y-4 sm:flex-row sm:items-center sm:text-left sm:space-y-0 sm:space-x-6 my-6">

                    <div
                        class="w-28 h-28 bg-slate-200 rounded-full flex items-center justify-center border-4 border-white shadow-sm overflow-hidden flex-shrink-0">

                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </template>

                        <template x-if="!photoPreview">
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto Profil"
                                    class="w-full h-full object-cover">
                            @else
                                <svg class="w-20 h-20 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            @endif
                        </template>
                    </div>

                    <input type="file" name="photo" id="photo" class="hidden" x-ref="photo"
                        x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        ">

                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Foto Profil</h2>

                        <button type="button" x-on:click.prevent="$refs.photo.click()"
                            class="mt-2 text-sm font-medium text-green-700 bg-green-100 hover:bg-green-200 px-4 py-2 rounded-full">
                            Pilih File Baru
                        </button>

                        <div x-show="photoName" class="text-sm text-gray-500 mt-2">
                            File terpilih: <span x-text="photoName" class="font-semibold"></span>
                        </div>

                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                @if (session('status'))
                    <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">{{ old('alamat', $user->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div> -->
                    <div class="md:col-span-2">
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700">No Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon"
                            value="{{ old('no_telepon', $user->no_telepon) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        @error('no_telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div
                    class="flex flex-col-reverse mt-8 space-y-4 space-y-reverse sm:flex-row sm:justify-end sm:space-y-0 sm:space-x-4">
                    <a href="{{ url()->previous() }}"
                        class="w-full rounded-md border border-gray-300 px-6 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto">
                        Batal
                    </a>
                    <button type="submit"
                        class="w-full rounded-md border border-transparent bg-green-700 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-800 sm:w-auto">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
