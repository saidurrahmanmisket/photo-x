@extends('backend.app')
@section('title', 'Edit Frame')
@section('header_title', 'Edit Frame')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bg-white p-5">
                    <form action="{{ route('admin.frames.update', $frame->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $frame->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            <small class="text-muted">Supported formats: JPEG, PNG, JPG, SVG, WebP (max 5MB)</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($frame->image)
                                <img src="{{ asset($frame->image) }}" alt="Frame Image" class="img-thumbnail mt-2" style="max-width: 150px;">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="grid_columns" class="form-label">Grid Columns</label>
                            <input type="number" class="form-control @error('grid_columns') is-invalid @enderror" id="grid_columns" name="grid_columns" min="1" value="{{ old('grid_columns', $frame->grid_columns) }}" required>
                            @error('grid_columns')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="grid_rows" class="form-label">Grid Rows</label>
                            <input type="number" class="form-control @error('grid_rows') is-invalid @enderror" id="grid_rows" name="grid_rows" min="1" value="{{ old('grid_rows', $frame->grid_rows) }}" required>
                            @error('grid_rows')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (BDT)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" min="0" step="0.01" value="{{ old('price', $frame->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.frames.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
