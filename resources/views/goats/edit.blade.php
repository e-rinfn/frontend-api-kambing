@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Kambing</h4>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('goats/' . $goat['id']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Field No Tag -->
                            <div class="mb-3">
                                <label for="noTag" class="form-label">No Tag Kambing</label>
                                <input type="text" class="form-control @error('noTag') is-invalid @enderror"
                                    id="noTag" name="noTag" value="{{ old('noTag', $goat['noTag']) }}" required>
                                @error('noTag')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-6">
                            <!-- Field Tanggal Lahir -->
                            <div class="col-md-6">
                                <!-- Field Tanggal Lahir -->
                                <div class="mb-3">
                                    <label for="tanggalLahir" class="form-label">Tanggal Lahir</label>
                                    @php
                                        // Pastikan tanggal dalam format Y-m-d
                                        $tanggalLahir = isset($goat['tanggalLahir'])
                                            ? \Carbon\Carbon::parse($goat['tanggalLahir'])->format('Y-m-d')
                                            : null;
                                    @endphp
                                    <input type="date" class="form-control @error('tanggalLahir') is-invalid @enderror"
                                        id="tanggalLahir" name="tanggalLahir"
                                        value="{{ old('tanggalLahir', $tanggalLahir) }}" required>
                                    @error('tanggalLahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Field Nama -->
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" value="{{ old('nama', $goat['nama']) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Field Bobot -->
                            <div class="mb-3">
                                <label for="bobot" class="form-label">Bobot (KG)</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('bobot') is-invalid @enderror" id="bobot" name="bobot"
                                    value="{{ old('bobot', $goat['bobot']) }}" required>
                                @error('bobot')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Field Kelamin -->
                            <div class="mb-3">
                                <label for="kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-control @error('kelamin') is-invalid @enderror" id="kelamin"
                                    name="kelamin" required>
                                    <option value="" disabled>Pilih Jenis Kelamin</option>
                                    <option value="Jantan"
                                        {{ old('kelamin', $goat['kelamin']) == 'Jantan' ? 'selected' : '' }}>Jantan
                                    </option>
                                    <option value="Betina"
                                        {{ old('kelamin', $goat['kelamin']) == 'Betina' ? 'selected' : '' }}>Betina
                                    </option>
                                </select>
                                @error('kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Field Jenis -->
                            <div class="mb-3">
                                <label for="jenis" class="form-label">Jenis Kambing</label>
                                <input type="text" class="form-control @error('jenis') is-invalid @enderror"
                                    id="jenis" name="jenis" value="{{ old('jenis', $goat['jenis']) }}" required>
                                @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Field Induk -->
                            <div class="mb-3">
                                <label for="induk" class="form-label">Nama Induk</label>
                                <input type="text" class="form-control" id="induk" name="induk"
                                    value="{{ old('induk', $goat['induk']) }}" required>
                                @error('induk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Field Pejantan -->
                            <div class="mb-3">
                                <label for="pejantan" class="form-label">Nama Pejantan</label>
                                <input type="text" class="form-control" id="pejantan" name="pejantan"
                                    value="{{ old('pejantan', $goat['pejantan']) }}" required>
                                @error('pejantan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Field Posisi Kandang -->
                            <div class="mb-3">
                                <label for="posisiKandang" class="form-label">Posisi Kandang</label>
                                <input type="text" class="form-control" id="posisiKandang" name="posisiKandang"
                                    value="{{ old('posisiKandang', $goat['posisiKandang']) }}" required>
                                @error('posisiKandang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Field Asal -->
                            <div class="mb-3">
                                <label for="asal" class="form-label">Asal</label>
                                <input type="text" class="form-control" id="asal" name="asal"
                                    value="{{ old('asal', $goat['asal']) }}" required>
                                @error('asal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Field Harga -->
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="number" class="form-control" id="harga" name="harga"
                                    value="{{ old('harga', $goat['harga']) }}" required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Field Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="tersedia"
                                        {{ old('status', $goat['status']) == 'tersedia' ? 'selected' : '' }}>Tersedia
                                    </option>
                                    <option value="terjual"
                                        {{ old('status', $goat['status']) == 'terjual' ? 'selected' : '' }}>Terjual
                                    </option>
                                    <option value="mati"
                                        {{ old('status', $goat['status']) == 'mati' ? 'selected' : '' }}>Mati</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success px-5">Update</button>

                    </div>
                    <br>
                </form>
            </div>
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
@endsection
