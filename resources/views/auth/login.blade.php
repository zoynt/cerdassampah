@extends('layouts.guest')

@section('title', 'Login - CerdasSampah.id')

@section('content')
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full">

        <div class="flex justify-center mb-4">
            {{-- Ganti 'img/logo-maskot.png' dengan path ke logo robot Anda --}}
            <img src="{{ asset('img/logosec.png') }}" alt="Logo" class="h-20">
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Masuk ke Akun Anda</h2>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
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

            <div x-data="{ show: false }">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Kata sandi</label>
                <div class="flex items-center bg-slate-100 rounded-lg focus-within:ring-2 focus-within:ring-green-500">

                    <input :type="show ? 'text' : 'password'" id="password" name="password"
                        placeholder="Masukkan kata sandi anda"
                        class="bg-transparent border-none w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none"
                        required>

                    {{-- Tombol untuk toggle lihat password (menggunakan SVG) --}}
                    <button type="button" @click="show = !show" class="px-3 focus:outline-none">

                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd"
                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clip-rule="evenodd" />
                        </svg>

                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                            viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd"
                                d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                clip-rule="evenodd" />
                            <path
                                d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.742L2.335 6.578A10.025 10.025 0 01.458 10c1.274 4.057 5.022 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                        </svg>
                    </button>

                    <span class="pr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
                <a href="{{ route('password.request') }}"
                    class="mt-2 inline-block align-baseline float-right text-sm text-green-700 hover:text-green-800 font-semibold">
                    Lupa kata sandi?
                </a>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-[#6D936C] text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline hover:bg-opacity-90 transition-colors">
                    Masuk
                </button>
            </div>

            <div class="flex items-center justify-center">
                <hr class="w-full border-gray-300">
                <span class="px-2 text-gray-500 text-sm">atau</span>
                <hr class="w-full border-gray-300">
            </div>

            <div>
                <a href="{{ route('register') }}"
                    class="w-full block text-center border border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline hover:bg-gray-100 transition-colors">
                    Daftar
                </a>
            </div>
        </form>
    </div>
@endsection
