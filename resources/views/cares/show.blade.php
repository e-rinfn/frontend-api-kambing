@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Detail Perawatan</h1>

        <!-- Detail Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h2>Informasi Perawatan</h2>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $care['id'] }}</p>
                <p><strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($care['tanggal'])->locale('id')->translatedFormat('d F Y') }}</p>
                <p><strong>Tipe Perawatan:</strong> {{ $care['tipePerawatan'] }}</p>
                <p><strong>Catatan:</strong> {{ $care['catatan'] }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('goats.cares.edit', [$goatId, $care['id']]) }}" class="btn btn-warning">Edit</a>

            <form action="{{ route('goats.cares.destroy', [$goatId, $care['id']]) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>

            <a href="{{ route('goats.cares.index', $goatId) }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
