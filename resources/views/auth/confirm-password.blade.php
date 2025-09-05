@extends('layouts.guest')

@section('title', 'Login - CerdasSampah.id')

@section('content')
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full">

        <div class="flex justify-center mb-4">
            {{-- Ganti 'img/logo-maskot.png' dengan path ke logo robot Anda --}}
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-20">
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">Konfirmasi Kata Sandi</h2>
        <p class="mb-4 text-sm text-center text-gray-600 dark:text-gray-400">
            {{ __('Silahkan konfirmasi kata sandi anda sebelum melanjutkan') }}
        </p>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <!-- Password -->
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
            {{-- <div>
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div> --}}
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-[#6D936C] text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline hover:bg-opacity-90 transition-colors">
                    Reset
                </button>
            </div>
            {{-- <div class="flex justify-end mt-4">
                <x-primary-button>
                    {{ __('Confirm') }}
                </x-primary-button>
            </div> --}}
        </form>
    @endsection
