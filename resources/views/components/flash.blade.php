@if (session('status'))
    <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4 text-red-700">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4">
        <p class="mb-2 font-semibold text-red-700">Terjadi kesalahan:</p>
        <ul class="list-inside list-disc text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
