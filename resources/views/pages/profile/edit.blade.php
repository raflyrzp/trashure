@extends('layouts.app')

@section('content')
    <div class="max-w-2xl">
        <div class="panel">
            <h1 class="panel-title">Ubah Profil</h1>

            <form action="{{ route('profile.update') }}" method="POST" class="mt-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="label">Nama</label>
                    <input type="text" id="name" name="name" class="input" value="{{ old('name', $user->name) }}"
                        required>
                </div>

                <div>
                    <label for="email" class="label">Email</label>
                    <input type="email" id="email" name="email" class="input"
                        value="{{ old('email', $user->email) }}" required>
                </div>

                <div>
                    <label for="phone_number" class="label">Nomor Telepon</label>
                    <input type="text" id="phone_number" name="phone_number" class="input"
                        value="{{ old('phone_number', $user->phone_number) }}">
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="password" class="label">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" class="input"
                            placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <div>
                        <label for="password_confirmation" class="label">Konfirmasi Kata Sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="input">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('profile.show') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
