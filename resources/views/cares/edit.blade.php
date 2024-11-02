@extends('layouts.app')

@section('content')
    <h1>Edit Perawatan Kambing</h1>

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
    <form action="{{ route('goats.cares.update', [$goatId, $care['id']]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="tanggal">Tanggal Perawatan</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control"
                value="{{ old('tanggal', \Illuminate\Support\Carbon::parse($care['tanggal'])->format('Y-m-d')) }}" required>
            @error('tanggal')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="tipePerawatan">Tipe Perawatan</label>
            <select name="tipePerawatan" id="tipePerawatan" class="form-control" required>
                <option value="" disabled>Pilih Tipe Perawatan</option>
                <option value="Vaksinasi"
                    {{ old('tipePerawatan', $care['tipePerawatan']) == 'Vaksinasi' ? 'selected' : '' }}>Vaksinasi</option>
                <option value="Pemeriksaan"
                    {{ old('tipePerawatan', $care['tipePerawatan']) == 'Pemeriksaan' ? 'selected' : '' }}>Pemeriksaan
                </option>
                <option value="Penyembuhan"
                    {{ old('tipePerawatan', $care['tipePerawatan']) == 'Penyembuhan' ? 'selected' : '' }}>Penyembuhan
                </option>
                <!-- Tambahkan opsi lain sesuai kebutuhan -->
            </select>
            @error('tipePerawatan')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="catatan">Catatan</label>
            <textarea name="catatan" id="catatan" class="form-control" rows="3" required>{{ old('catatan', $care['catatan']) }}</textarea>
            @error('catatan')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('goats.cares.index', $goatId) }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
