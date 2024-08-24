@extends('layouts.app')

@section('content')
    <h1>Tambah Perawatan Kambing</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('goats.cares.store', $goatId) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="tanggal">Tanggal Perawatan</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="tipePerawatan">Tipe Perawatan</label>
            <input type="text" name="tipePerawatan" id="tipePerawatan" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="catatan">Catatan</label>
            <input type="text" name="catatan" id="catatan" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('goats.cares.index', $goatId) }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
