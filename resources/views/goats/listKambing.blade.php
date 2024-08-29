@extends('layouts.app')

@section('content')
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                <h4>Manage Goats</h4>
                <a href="{{ url('goats/create') }}" class="btn btn-primary btn-sm">Add Goat</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            @foreach ($goats as $goat)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $goat['nama'] ?? 'Unknown Name' }}</h5>
                            <p class="card-text"><strong>No Tag:</strong> {{ $goat['noTag'] ?? 'No Tag Available' }}</p>
                            <p class="card-text"><strong>Bobot:</strong> {{ $goat['bobot'] ?? 'N/A' }} KG</p>
                            <p class="card-text"><strong>Kelamin:</strong> {{ $goat['kelamin'] ?? 'N/A' }}</p>
                            <p class="card-text"><strong>Status:</strong> {{ $goat['status'] ?? 'N/A' }}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <!-- View Button -->
                            <a href="{{ url('goats/' . $goat['id']) }}" class="btn btn-info btn-sm w-100 me-1">View</a>

                            <!-- Edit Button -->
                            <a href="{{ url('goats/' . $goat['id'] . '/edit') }}"
                                class="btn btn-warning btn-sm w-100 me-1">Edit</a>

                            <!-- Care Button -->
                            <a href="{{ url('goats/' . $goat['id'] . '/cares') }}"
                                class="btn btn-secondary btn-sm w-100 me-1">Perawatan</a>

                            <!-- Delete Form -->
                            <form action="{{ url('goats/' . $goat['id']) }}" method="POST" class="w-100 me-1"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">Delete</button>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
@endsection
