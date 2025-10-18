@extends('layouts.app')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <h1 class="panel-title">Riwayat Laporan</h1>
            <a href="{{ route('reports.create') }}" class="btn-primary">Tambah Laporan</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="th">Foto</th>
                        <th class="th">Deskripsi</th>
                        <th class="th">Lokasi</th>
                        <th class="th">Jenis</th>
                        <th class="th">Status</th>
                        <th class="th">Dilaporkan</th>
                        <th class="th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-white">
                    @forelse ($reports as $report)
                        <tr>
                            <td class="td">
                                <img class="h-14 w-20 rounded object-cover"
                                    src="{{ $report->photo_url ? asset('storage/' . $report->photo_url) : 'https://placehold.co/160x112?text=Foto' }}"
                                    alt="Foto laporan">
                            </td>
                            <td class="td">{{ Str::limit($report->description, 80) }}</td>
                            <td class="td">{{ $report->location }}</td>
                            <td class="td">{{ $report->wasteType?->name ?? '-' }}</td>
                            <td class="td"><span class="badge {{ $report->status }}"></span></td>
                            <td class="td">{{ optional($report->reported_at)->format('d M Y H:i') }}</td>
                            <td class="td text-right">
                                <a class="btn-link" href="{{ route('reports.show', $report) }}">Detail</a>
                                @if ($report->status === \App\Models\Report::STATUS_PENDING && auth()->id() === $report->reporter_id)
                                    <form action="{{ route('reports.destroy', $report) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger"
                                            onclick="return confirm('Hapus laporan ini?')">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="td text-center text-gray-500 py-10">Belum ada laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    </div>
@endsection
