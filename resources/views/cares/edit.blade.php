@extends('layouts.app')

@section('content')
    <h1>Edit Perawatan Kambing</h1>

    <form action="{{ route('goats.cares.update', [$goatId, $care['id']]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama Perawatan</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $care['name'] }}" required>
        </div>

        <div class="form-group">
            <label for="date">Tanggal</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $care['date'] }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('goats.cares.index', $goatId) }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
