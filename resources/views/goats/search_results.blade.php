@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Search Results</h2>
        @if ($goats)
            <div class="row">
                @foreach ($goats as $goat)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $goat['nama'] }}</h5>
                                <p class="card-text"><strong>No Tag:</strong> {{ $goat['noTag'] }}</p>
                                <p class="card-text"><strong>Bobot:</strong> {{ $goat['bobot'] }} KG</p>
                                <p class="card-text"><strong>Kelamin:</strong> {{ $goat['kelamin'] }}</p>
                                <p class="card-text"><strong>Status:</strong> {{ $goat['status'] }}</p>
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
        @else
            <p>No goats found.</p>
        @endif
    </div>
@endsection
