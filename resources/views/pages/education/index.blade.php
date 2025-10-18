@extends('layouts.app')

@section('content')
    <div class="panel">
        <div class="panel-header">
            <h1 class="panel-title">Panduan & Edukasi Pemilahan Sampah</h1>
            <p class="text-sm text-gray-500">Pelajari cara memilah sampah dengan benar untuk lingkungan yang lebih bersih.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($educations as $e)
                <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <a href="{{ route('education.show', $e->slug) }}"
                            class="hover:text-emerald-700">{{ $e->title }}</a>
                    </h3>
                    <p class="mt-2 line-clamp-3 text-sm text-gray-600">{{ Str::limit(strip_tags($e->content), 140) }}</p>
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                        <span>{{ $e->category ?? 'Umum' }}</span>
                        <time>{{ optional($e->published_at)->format('d M Y') }}</time>
                    </div>
                </article>
            @empty
                <p class="text-gray-500">Belum ada materi edukasi.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $educations->links() }}
        </div>
    </div>
@endsection
