@extends('layouts.guest')

@section('title', 'Login - CerdasSampah.id')

@section('content')
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full">

        <div class="flex justify-center mb-4">
            {{-- Ganti 'img/logo-maskot.png' dengan path ke logo robot Anda --}}
            <img src="{{ asset('img/logosec.png') }}" alt="Logo" class="h-20">
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">Reset Kata Sandi</h2>
        <p class="mb-4 text-sm text-center text-gray-600 dark:text-gray-400">
            Silahkan masukkan email Anda untuk mengatur ulang kata sandi
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
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
            <!-- Email Address -->
            {{-- <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div> --}}
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-[#6D936C] text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline hover:bg-opacity-90 transition-colors">
                    Reset
                </button>
            </div>

            <div class="flex items-center justify-center">
                <hr class="w-full border-gray-300">
                <span class="px-2 text-gray-500 text-sm">atau</span>
                <hr class="w-full border-gray-300">
            </div>

            <div>
                <a href="{{ route('login') }}"
                    class="w-full block text-center border border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline hover:bg-gray-100 transition-colors">
                    Kembali
                </a>
            </div>

        </form>
    @endsection
