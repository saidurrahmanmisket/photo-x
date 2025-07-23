@extends('backend.app')
@section('title', 'Theme Details')
@section('header_title', 'Theme Details')

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Theme Details</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Name:</label>
                                <div>{{ $theme->name }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Price:</label>
                                <div>{{ $theme->price }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Image:</label>
                                @if($theme->image)
                                    <div>
                                        <img src="{{ asset($theme->image) }}" alt="Theme Image" style="max-width: 200px;">
                                    </div>
                                @else
                                    <div>No image uploaded.</div>
                                @endif
                            </div>
                            <a href="{{ route('admin.themes.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 