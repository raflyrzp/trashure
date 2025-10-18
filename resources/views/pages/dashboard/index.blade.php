@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="card">
            <div class="card-title">Total Laporan</div>
            <div class="card-value">{{ $totalReports }}</div>
        </div>
        <div class="card">
            <div class="card-title">Total Poin</div>
            <div class="card-value">{{ $totalPoints }}</div>
        </div>
        <div class="card">
            <div class="card-title">Peringkat Anda</div>
            <div class="card-value">
                @php
                    $rank = null;
                @endphp
                <span class="text-gray-500 text-base">Ditentukan dari leaderboard</span>
            </div>
        </div>
        <div class="card">
            <div class="card-title">Aksi Cepat</div>
            <div class="mt-3 flex flex-wrap gap-2">
                <a href="{{ route('reports.create') }}" class="btn-primary">Tambah Laporan</a>
                <a href="{{ route('reports.index') }}" class="btn-secondary">Riwayat</a>
            </div>
        </div>
    </div>

    <div class="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">Laporan Terbaru Anda</h3>
                    <a href="{{ route('reports.index') }}" class="link">Lihat semua</a>
                </div>
                <div class="divide-y">
                    @forelse ($recentReports as $r)
                        <div class="flex items-center justify-between py-4">
                            <div>
                                <p class="font-medium text-gray-900">{{ Str::limit($r->description, 80) }}</p>
                                <p class="text-sm text-gray-500">Lokasi: {{ $r->location }} • Status: <span
                                        class="badge {{ $r->status }}"></span></p>
                            </div>
                            <a href="{{ route('reports.show', $r) }}" class="btn-link">Detail</a>
                        </div>
                    @empty
                        <p class="py-6 text-gray-500">Belum ada laporan. Ayo kirim laporan pertamamu!</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div>
            <div class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">Papan Peringkat</h3>
                    <a href="{{ route('leaderboard.index') }}" class="link">Lihat semua</a>
                </div>
                <ul class="divide-y">
                    @foreach ($topUsers as $i => $u)
                        <li class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold">{{ $i + 1 }}</span>
                                <span class="font-medium text-gray-900">{{ $u->name }}</span>
                            </div>
                            <span class="text-sm text-gray-600">{{ $u->total_points }} poin</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
