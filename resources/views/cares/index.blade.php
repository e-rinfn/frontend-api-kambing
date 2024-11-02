@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Daftar Perawatan Kambing</h1>

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

        <!-- Button to Add Care -->
        <a href="{{ route('goats.cares.create', $goatId) }}" class="btn btn-primary mb-3">Tambah Perawatan</a>

        <!-- Care List Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perawatan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($careEvents as $care)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $care['tipePerawatan'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($care['tanggal'])->locale('id')->translatedFormat('d F Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('goats.cares.show', [$goatId, $care['id']]) }}"
                                        class="btn btn-info btn-sm">Lihat</a>
                                    <a href="{{ route('goats.cares.edit', [$goatId, $care['id']]) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('goats.cares.destroy', [$goatId, $care['id']]) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
