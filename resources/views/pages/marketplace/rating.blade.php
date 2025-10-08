@extends('layouts.dashboard')

@section('title', 'Beri Ulasan')

@section('content')
    <div class="space-y-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $review ? 'Edit Ulasan' : 'Beri Ulasan' }}</h1>

        <div
            class="bg-green-700 text-white p-4 rounded-xl shadow-md flex justify-between items-center text-sm md:text-base font-semibold">
            <span>No Order</span>
            {{-- [UBAH] Tampilkan nomor order dinamis --}}
            <span class="tracking-wider">{{ $order->order_number }}</span>
        </div>

        <form action="{{ route('marketplace.rating.store', $order) }}" method="POST">
            <form action="{{ route('marketplace.rating.store', $order) }}" method="POST">
                @csrf
                {{-- [UBAH x-data] Inisialisasi rating dengan data yang sudah ada --}}
                <div x-data="{ rating: {{ $review->rating ?? 0 }}, hoverRating: 0 }" class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="font-semibold text-gray-800 text-base md:text-lg">Beri Ulasan & Rating</h3>

                    <input type="hidden" name="rating" x-model="rating">

                    <div class="flex items-center justify-center space-x-1 md:space-x-2 my-6">
                        <template x-for="star in 5" :key="star">
                            <button type="button" @click="rating = star" @mouseenter="hoverRating = star"
                                @mouseleave="hoverRating = 0"
                                class="text-4xl md:text-5xl text-gray-300 focus:outline-none transition-transform transform hover:scale-110"
                                :class="{ '!text-yellow-400': (hoverRating >= star || rating >= star) }">
                                &#9733;
                            </button>
                        </template>
                    </div>

                    <div>
                        <label for="review" class="block text-sm font-medium text-gray-700 mb-1">Ulasan (Opsional)</label>
                        {{-- [UBAH] Tambahkan atribut name="review" --}}
                        <textarea id="review" name="review" rows="4" placeholder="Bagaimana kualitas produk ini?"
                            class="w-full border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm p-3 md:p-4">{{ $review->review ?? '' }}</textarea>
                    </div>

                    <div class="mt-4 flex justify-end">
                        {{-- [UBAH] Ganti jadi tombol submit dan tambahkan kondisi disabled --}}
                        <button type="submit" :disabled="rating === 0"
                            :class="{ 'opacity-50 cursor-not-allowed': rating === 0 }"
                            class="px-6 py-2 bg-green-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-green-600 disabled:bg-green-400">
                            Kirim Ulasan
                        </button>
                    </div>
                </div>
            </form>

            <div class="pt-2 flex flex-col md:flex-row justify-end items-center gap-4">
                {{-- Kembali ke halaman detail pembelian sebelumnya --}}
                <a href="{{ route('marketplace.purchase.detail', $order) }}"
                    class="w-full text-center px-6 py-2.5 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300">
                    Kembali
                </a>
            </div>
    </div>
@endsection
