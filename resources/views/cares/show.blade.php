@extends('layouts.app')

@section('content')
    <h1>Detail Perawatan</h1>
    <p>ID: {{ $care['id'] }}</p>
    <p>Tanggal: {{ $care['tanggal'] }}</p>
    <p>Tipe Perawatan: {{ $care['tipePerawatan'] }}</p>
    <p>Catatan: {{ $care['catatan'] }}</p>
    <a href="{{ route('goats.cares.edit', [$goatId, $care['id']]) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('goats.cares.destroy', [$goatId, $care['id']]) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Hapus</button>
    </form>
    <a href="{{ route('goats.cares.index', $goatId) }}" class="btn btn-secondary">Kembali</a>
@endsection
