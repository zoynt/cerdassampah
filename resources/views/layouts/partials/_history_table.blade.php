@forelse ($reports as $report)
    <tr class="border-b border-gray-200 hover:bg-gray-50">
        <td class="text-left py-3 px-4">
            {{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
        <td class="text-left py-3 px-4">{{ $report->name }}</td>
        <td class="text-left py-3 px-4">{{ $report->email }}</td>
        <td class="text-left py-3 px-4 w-1/4">{{ Str::limit($report->address, 50) }}</td>
        <td class="text-left py-3 px-4">
            {{ \Carbon\Carbon::parse($report->waktu_lapor)->isoFormat('D MMMM YYYY') }}</td>
        <td class="text-left py-3 px-4">
            <a href="{{ asset('storage/' . $report->image) }}" target="_blank"
                class="text-blue-500 hover:underline font-semibold">Lihat Foto</a>
        </td>
        <td class="text-left py-3 px-4">
            @if ($report->status == 'pending')
                <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs font-medium">Pending</span>
            @elseif($report->status == 'proses')
                <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs font-medium">Proses</span>
            @else
                <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs font-medium">Diterima</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-6">
            <p class="text-gray-500">Anda belum membuat laporan, atau data tidak ditemukan.</p>
        </td>
    </tr>
@endforelse
