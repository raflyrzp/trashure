<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trashure</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-full">
    <header class="bg-white shadow">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Top">
            <div class="flex w-full items-center justify-between py-4">
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <svg class="h-7 w-7 text-emerald-600" viewBox="0 0 24 24" fill="currentColor"
                            aria-hidden="true">
                            <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 15h-2v-2h2Zm0-4h-2V7h2Z" />
                        </svg>
                        <span class="font-semibold text-gray-900 text-lg">Trashure</span>
                    </a>
                    <div class="hidden md:flex items-center gap-4">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="nav-link" href="{{ route('reports.index') }}">Laporan</a>
                        <a class="nav-link" href="{{ route('reports.create') }}">Tambah Laporan</a>
                        <a class="nav-link" href="{{ route('leaderboard.index') }}">Leaderboard</a>
                        <a class="nav-link" href="{{ route('education.index') }}">Panduan</a>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a class="hidden md:inline-flex nav-link" href="{{ route('profile.show') }}">Profil</a>
                    <div class="relative md:hidden">
                        <button id="menuBtn"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div id="mobileMenu"
                            class="absolute right-0 z-50 mt-2 hidden w-48 rounded-lg bg-white p-2 shadow-lg ring-1 ring-black/5">
                            <a class="mobile-link" href="{{ route('dashboard') }}">Dashboard</a>
                            <a class="mobile-link" href="{{ route('reports.index') }}">Laporan</a>
                            <a class="mobile-link" href="{{ route('reports.create') }}">Tambah Laporan</a>
                            <a class="mobile-link" href="{{ route('leaderboard.index') }}">Leaderboard</a>
                            <a class="mobile-link" href="{{ route('education.index') }}">Panduan</a>
                            <a class="mobile-link" href="{{ route('profile.show') }}">Profil</a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('components.flash')
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    <footer class="border-t bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 flex justify-between">
            <p>&copy; {{ date('Y') }} Trashure. Semua hak dilindungi.</p>
            <p class="hidden sm:block">Bersama kita jaga kebersihan lingkungan.</p>
        </div>
    </footer>

    <script>
        const btn = document.getElementById('menuBtn');
        const menu = document.getElementById('mobileMenu');
        if (btn) {
            btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        }
    </script>
</body>

</html>
