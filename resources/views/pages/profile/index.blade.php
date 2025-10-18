@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="panel">
                <div class="panel-header">
                    <h1 class="panel-title">Profil</h1>
                    <a href="{{ route('profile.edit') }}" class="btn-primary">Ubah Profil</a>
                </div>

                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="dt">Nama</dt>
                        <dd class="dd">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="dt">Email</dt>
                        <dd class="dd">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="dt">Nomor Telepon</dt>
                        <dd class="dd">{{ $user->phone_number ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="dt">Peran</dt>
                        <dd class="dd">{{ ucfirst($user->role) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div>
            <div class="panel">
                <h3 class="panel-title">Statistik</h3>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="stat">
                        <div class="stat-label">Total Laporan</div>
                        <div class="stat-value">{{ $stats['total_reports'] }}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Total Poin</div>
                        <div class="stat-value">{{ $stats['total_points'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
