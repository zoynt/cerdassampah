@extends('layouts.dashboard')

@section('title', 'Histori Laporan')
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">


@section('content')

    <div class="bg-white rounded-xl shadow-md p-6 sm:p-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">
            Histori Laporan
        </h1>

        <form id="search-form" action="{{ route('laporan.history') }}" method="GET" class="mb-6">
            <label for="search" class="block text-base font-semibold text-gray-600">Cari Laporan</label>
            <div class="relative mt-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Cari berdasarkan alamat atau status...">
                <button type="submit" class="hidden">Cari</button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-green-700 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm rounded-l-lg">No</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Lengkap</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Alamat</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Foto</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm rounded-r-lg">Status</th>
                    </tr>
                </thead>
                <tbody id="history-table-body" class="text-gray-700">
                    @include('layouts.partials._history_table', ['reports' => $reports])
                </tbody>
            </table>
        </div>

        <div id="pagination-container" class="mt-6">
            {{ $reports->links() }}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('search-form');
            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('history-table-body');
            const paginationContainer = document.getElementById('pagination-container');
            let searchTimeout;

            function performSearch() {
                const formData = new FormData(searchForm);
                const params = new URLSearchParams(formData);
                const url = `${searchForm.action}?${params.toString()}`;
                history.pushState(null, '', url);


                tableBody.innerHTML =
                    `<tr><td colspan="7" class="text-center p-6 animate-pulse">Mencari...</td></tr>`;
                paginationContainer.innerHTML = '';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {

                        tableBody.innerHTML = data.table_html;
                        paginationContainer.innerHTML = data.pagination_html;
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        tableBody.innerHTML =
                            `<tr><td colspan="7" class="text-center p-6 text-red-500">Gagal memuat data.</td></tr>`;
                    });
            }

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
            });

            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    performSearch();
                }, 500);
            });
        });
    </script>
@endpush
