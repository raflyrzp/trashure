@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="panel">
                <div class="panel-header">
                    <h1 class="panel-title">Detail Laporan</h1>
                    <span class="badge {{ $report->status }}"></span>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <img class="aspect-[4/3] w-full rounded-lg object-cover ring-1 ring-gray-200"
                            src="{{ $report->photo_url ? asset('storage/' . $report->photo_url) : 'https://placehold.co/800x600?text=Foto' }}"
                            alt="Foto laporan">
                    </div>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Deskripsi</h3>
                            <p class="mt-1 text-gray-900">{{ $report->description }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Lokasi</h3>
                            <p class="mt-1 text-gray-900">{{ $report->location }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Jenis Sampah</h3>
                                <p class="mt-1 text-gray-900">{{ $report->wasteType?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Dilaporkan</h3>
                                <p class="mt-1 text-gray-900">{{ optional($report->reported_at)->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Pelapor</h3>
                                <p class="mt-1 text-gray-900">{{ $report->reporter?->name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Diverifikasi</h3>
                                <p class="mt-1 text-gray-900">
                                    {{ optional($report->verified_at)->format('d M Y H:i') ?? '-' }}</p>
                            </div>
                        </div>
                        @if ($report->admin_notes)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Catatan Admin</h3>
                                <p class="mt-1 whitespace-pre-line text-gray-900">{{ $report->admin_notes }}</p>
                            </div>
                        @endif
                        @if ($report->pointHistory)
                            <div class="rounded-md border border-emerald-200 bg-emerald-50 p-3 text-emerald-800">
                                Poin diberikan: <strong>{{ $report->pointHistory->points }}</strong> pada
                                {{ optional($report->pointHistory->granted_at)->format('d M Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if (auth()->id() === $report->reporter_id && $report->status === \App\Models\Report::STATUS_PENDING)
                <div class="panel">
                    <h3 class="panel-title">Aksi</h3>
                    <div class="mt-3">
                        <form action="{{ route('reports.destroy', $report) }}" method="POST"
                            onsubmit="return confirm('Hapus laporan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger">Hapus Laporan</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        @if (auth()->user()?->isAdmin())
            <div>
                <div class="panel sticky top-6">
                    <h3 class="panel-title">Panel Admin</h3>
                    <form action="{{ route('reports.updateStatus', $report) }}" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label for="status" class="label">Ubah Status</label>
                            <select id="status" name="status" class="input" required>
                                @foreach (\App\Models\Report::STATUSES as $s)
                                    <option value="{{ $s }}" @selected($report->status === $s)>{{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="admin_notes" class="label">Catatan Admin (opsional)</label>
                            <textarea id="admin_notes" name="admin_notes" rows="3" class="input"
                                placeholder="Tuliskan alasan atau catatan...">{{ old('admin_notes', $report->admin_notes) }}</textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full">Simpan Perubahan</button>
                        <p class="text-xs text-gray-500">Catatan: Poin diberikan saat status menjadi "verified" atau
                            "resolved", dan dicabut jika kembali ke "pending" atau "rejected".</p>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
