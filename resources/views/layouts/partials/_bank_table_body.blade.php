@forelse ($schedules as $index => $schedule)
    {{-- [PERBAIKAN] Menggunakan kolom 'latitude' dan 'longitude' --}}
    <tr class="bg-white border-b hover:bg-green-50 transition-colors duration-200 cursor-pointer bank-row"
        data-id="{{ $schedule->id }}" data-lat="{{ $schedule->latitude }}" data-lng="{{ $schedule->longitude }}">
        
        <td class="px-6 py-4 font-medium text-gray-900">{{ $loop->iteration + $schedules->firstItem() - 1 }}</td>
        <td class="px-6 py-4 font-semibold text-gray-800">{{ $schedule->bank_name }}</td>
        
        {{-- [PERBAIKAN] Menggunakan kolom 'address' --}}
        <td class="px-6 py-4">{{ $schedule->address }}</td>
        
        {{-- [PERBAIKAN] Menggunakan kolom 'district' --}}
        <td class="px-6 py-4">{{ $schedule->district }}</td>
        
        <td class="px-6 py-4">
            @if(is_array($schedule->operational_days))
                {{-- Langsung implode, karena $schedule->operational_days SUDAH array --}}
                {{ implode(', ', $schedule->operational_days) }}
            @endif
        </td>
        
        {{-- [PERBAIKAN] Menggunakan 'opening_hour' dan 'closing_hour' --}}
        <td class="px-6 py-4">{{ date('H:i', strtotime($schedule->opening_hour)) }} -
            {{ date('H:i', strtotime($schedule->closing_hour)) }} WITA</td>
    </tr>
@empty
    <tr class="bg-white border-b">
        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
            Tidak ada data bank sampah yang ditemukan.
        </td>
    </tr>
@endforelse