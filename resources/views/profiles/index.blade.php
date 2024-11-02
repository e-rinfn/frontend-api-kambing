@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">User Profile</h3>
                    </div>
                    <div class="card-body">
                        @if (Session::has('user'))
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">User ID:</div>
                                <div class="col-sm-8">{{ Session::get('user.id') }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 font-weight-bold">Username:</div>
                                <div class="col-sm-8">{{ Session::get('user.username') }}</div>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                User data not available
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
