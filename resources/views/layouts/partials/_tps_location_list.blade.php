@forelse ($listTps as $tps)
    <div class="tps-list-item flex justify-between items-center border-b pb-3 cursor-pointer hover:bg-gray-50 transition-colors"
        data-id="{{ $tps->id }}">
        <div class="pr-4">
            <p class="font-semibold text-gray-800 text-base">{{ $tps->tps_name }}</p>
            <p class="text-gray-600 text-sm">{{ $tps->tps_address }}</p>
        </div>
        @if ($tps->tps_status == 'resmi')
            <span
                class="text-xs font-semibold text-green-800 bg-green-100 px-3 py-1 rounded-full flex-shrink-0">Resmi</span>
        @else
            <span
                class="text-xs font-semibold text-red-800 bg-red-100 px-3 py-1 rounded-full flex-shrink-0">Ilegal</span>
        @endif
    </div>
@empty
    <p class="text-gray-500 text-center">Tidak ada data lokasi TPS yang cocok dengan filter Anda.</p>
@endforelse
