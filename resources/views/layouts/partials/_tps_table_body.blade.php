@forelse ($schedules as $schedule)
    <tr class="bg-white border-b hover:bg-green-50 transition-colors duration-200 cursor-pointer tps-row"
        data-id="{{ $schedule->id }}" data-lat="{{ $schedule->tps_latitude }}" data-lng="{{ $schedule->tps_longitude }}">
        <td class="px-6 py-4 font-medium text-gray-900">{{ $loop->iteration + $schedules->firstItem() - 1 }}</td>
        <td class="px-6 py-4 font-semibold text-gray-800">{{ $schedule->tps_name }}</td>
        <td class="px-6 py-4">{{ $schedule->tps_address }}</td>
        <td class="px-6 py-4">{{ $schedule->kecamatan }}</td>
        <td class="px-6 py-4">{{ $schedule->tps_day }}</td>
        <td class="px-6 py-4">{{ date('H:i', strtotime($schedule->tps_start_time)) }} -
            {{ date('H:i', strtotime($schedule->tps_end_time)) }} WITA</td>
        <td class="px-6 py-4">{{ $schedule->tps_transport }}</td>
    </tr>
@empty
    <tr class="bg-white border-b">
        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
            Tidak ada data TPS yang ditemukan.
        </td>
    </tr>
@endforelse
