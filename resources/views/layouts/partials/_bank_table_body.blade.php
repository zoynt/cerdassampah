@forelse ($schedules as $schedule)
    <tr class="bg-white border-b hover:bg-green-50 transition-colors duration-200 cursor-pointer bank-row"
        data-id="{{ $schedule->id }}" data-lat="{{ $schedule->bank_latitude }}" data-lng="{{ $schedule->bank_longitude }}">
        <td class="px-6 py-4 font-medium text-gray-900">{{ $loop->iteration + $schedules->firstItem() - 1 }}</td>
        <td class="px-6 py-4 font-semibold text-gray-800">{{ $schedule->bank_name }}</td>
        <td class="px-6 py-4">{{ $schedule->bank_address }}</td>
        <td class="px-6 py-4">{{ $schedule->kecamatan }}</td>
        <td class="px-6 py-4">{{ $schedule->bank_day }}</td>
        <td class="px-6 py-4">{{ date('H:i', strtotime($schedule->bank_start_time)) }} -
            {{ date('H:i', strtotime($schedule->bank_end_time)) }} WITA</td>
    </tr>
@empty
    <tr class="bg-white border-b">
        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
            Tidak ada data bank sampah yang ditemukan.
        </td>
    </tr>
@endforelse
