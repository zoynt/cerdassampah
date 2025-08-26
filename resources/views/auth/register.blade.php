@extends('layouts.guest')

@section('title', 'Daftar - CerdasSampah.id')

@section('content')
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full">

        <div class="flex justify-center mb-4">
            {{-- Ganti 'img/logo-maskot.png' dengan path ke logo robot Anda --}}
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-20">
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Buat Akun Baru</h2>

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Nama Lengkap</label>
                <div class="flex items-center bg-slate-100 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap"
                        class="bg-transparent border-none w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none"
                        required>
                    <span class="p-3">
                        <i class="fa fa-user text-gray-400"></i>
                    </span>
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
                <div class="flex items-center bg-slate-100 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                        placeholder="Masukkan nama panggilan"
                        class="bg-transparent border-none w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none"
                        required>
                    <span class="p-3">
                        <i class="fa fa-user text-gray-400"></i>
                    </span>
                </div>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                <div class="flex items-center bg-slate-100 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Masukkan email anda"
                        class="bg-transparent border-none w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none"
                        required>
                    <span class="p-3">
                        <i class="fa fa-envelope text-gray-400"></i>
                    </span>
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Kata sandi</label>
                <div class="flex items-center bg-slate-100 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                        class="bg-transparent border-none w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none"
                        required>
                    <span class="p-3">
                        <i class="fa fa-lock text-gray-400"></i>
                    </span>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">Konfirmasi Kata
                    Sandi</label>
                <div class="flex items-center bg-slate-100 rounded-lg focus-within:ring-2 focus-within:ring-green-500">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Ulangi kata sandi"
                        class="bg-transparent border-none w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none"
                        required>
                    <span class="p-3">
                        <i class="fa fa-lock text-gray-400"></i>
                    </span>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-[#6D936C] text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline hover:bg-opacity-90 transition-colors">
                    Daftar
                </button>
            </div>

            <p class="text-center text-gray-600 text-sm pt-2">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-green-700 hover:text-green-800 font-bold">Masuk</a>
            </p>
        </form>
    </div>
@endsection
