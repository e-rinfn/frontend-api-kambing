@extends('layouts.app')

@section('content')
    <h1>Daftar Perawatan Kambing</h1>

    <!-- Display Goat Information -->
    <div class="mb-4">
        <h2>Informasi Kambing</h2>
        <p><strong>Nama Kambing:</strong> {{ $goat['nama'] }}</p>
        <p><strong>Nomor Tag:</strong> {{ $goat['noTag'] }}</p>
    </div>

    <!-- Button to Add Care -->
    <a href="{{ route('goats.cares.create', $goatId) }}" class="btn btn-primary mb-3">Tambah Perawatan</a>

    <!-- Care List Table -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Perawatan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($careEvents as $care)
                <tr>
                    <td>{{ $care['id'] }}</td>
                    <td>{{ $care['tipePerawatan'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($care['tanggal'])->locale('id')->translatedFormat('d F Y') }}</td>
                    <td>
                        <a href="{{ route('goats.cares.show', [$goatId, $care['id']]) }}" class="btn btn-info">Lihat</a>
                        <a href="{{ route('goats.cares.edit', [$goatId, $care['id']]) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('goats.cares.destroy', [$goatId, $care['id']]) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
