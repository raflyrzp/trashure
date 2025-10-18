@extends('layouts.app')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <h1 class="panel-title">Papan Peringkat</h1>
            <p class="text-sm text-gray-500">Urutan berdasarkan total poin tertinggi.</p>
        </div>

        <ul role="list" class="divide-y divide-gray-200">
            @foreach ($users as $index => $u)
                <li class="flex items-center justify-between py-4">
                    <div class="flex items-center gap-4">
                        <span
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-semibold">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</span>
                        <div>
                            <p class="font-medium text-gray-900">{{ $u->name }}</p>
                            <p class="text-sm text-gray-500">ID: {{ $u->id }}</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700">{{ $u->total_points }}
                        poin</span>
                </li>
            @endforeach
        </ul>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
@endsection
