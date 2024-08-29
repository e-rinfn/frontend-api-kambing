@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tambah Perawatan Kambing</h1>

        <!-- Display Goat Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h2>Informasi Kambing</h2>
            </div>
            <div class="card-body">
                <p><strong>Nama Kambing:</strong> {{ $goat['nama'] }}</p>
                <p><strong>Nomor Tag:</strong> {{ $goat['noTag'] }}</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('goats.cares.store', $goatId) }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="tanggal">Tanggal Perawatan</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="tipePerawatan">Tipe Perawatan</label>
                <select name="tipePerawatan" id="tipePerawatan" class="form-control" required>
                    <option value="" disabled selected>Pilih Tipe Perawatan</option>
                    <option value="Vaksinasi">Vaksinasi</option>
                    <option value="Pemeriksaan">Pemeriksaan</option>
                    <option value="Penyembuhan">Penyembuhan</option>
                    <!-- Tambahkan opsi lain sesuai kebutuhan -->
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="catatan">Catatan</label>
                <textarea name="catatan" id="catatan" class="form-control" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('goats.cares.index', $goatId) }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
