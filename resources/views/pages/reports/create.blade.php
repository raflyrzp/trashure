@extends('layouts.app')

@section('content')
    <div class="max-w-3xl">
        <div class="panel">
            <h1 class="panel-title">Tambah Laporan Sampah</h1>
            <p class="mt-1 text-sm text-gray-600">Isi formulir di bawah ini untuk melaporkan temuan sampah di sekitar Anda.
            </p>

            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                @csrf

                <div>
                    <label for="waste_type_id" class="label">Jenis Sampah</label>
                    <select id="waste_type_id" name="waste_type_id" class="input">
                        <option value="">Pilih jenis (opsional)</option>
                        @foreach ($wasteTypes as $t)
                            <option value="{{ $t->id }}" @selected(old('waste_type_id') == $t->id)>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="description" class="label">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="input"
                        placeholder="Ceritakan kondisi, volume, atau detail lainnya..." required>{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="location" class="label">Lokasi</label>
                    <input id="location" name="location" type="text" class="input"
                        placeholder="Contoh: Jl. Melati No. 10, dekat taman" value="{{ old('location') }}" required>
                </div>

                <div>
                    <label for="photo" class="label">Foto</label>
                    <input id="photo" name="photo" type="file" accept="image/*" class="input" required>
                    <p class="mt-1 text-xs text-gray-500">Format: JPG/PNG, maks 4MB.</p>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('reports.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
