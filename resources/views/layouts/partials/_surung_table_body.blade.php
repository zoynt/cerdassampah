@forelse ($schedules as $schedule)
    <tr class="bg-white border-b hover:bg-green-50 transition-colors duration-200 cursor-pointer surung-row"
        data-id="{{ $schedule->id }}" data-lat="{{ $schedule->surung_latitude }}"
        data-lng="{{ $schedule->surung_longitude }}">
        <td class="px-6 py-4 font-medium text-gray-900">{{ $loop->iteration + $schedules->firstItem() - 1 }}</td>
        <td class="px-6 py-4 font-semibold text-gray-800">{{ $schedule->area }}</td>
        <td class="px-6 py-4">
            @if(is_array($schedule->surung_day))
                {{ implode(', ', $schedule->surung_day) }}
            @endif
        </td>
        <td class="px-6 py-4">{{ date('H:i', strtotime($schedule->surung_start_time)) }} -
            {{ date('H:i', strtotime($schedule->surung_end_time)) }} WITA</td>
        <td class="px-6 py-4">{{ $schedule->worker_name }}</td>
        <td class="px-6 py-4">{{ $schedule->tps->tps_name ?? 'N/A' }}</td>
    </tr>
@empty
    <tr class="bg-white border-b">
        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
            Tidak ada data jadwal yang ditemukan.
        </td>
    </tr>
@endforelse
