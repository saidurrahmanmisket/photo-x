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
                            <input type="text" class="form-control" id="name" name="name" value="{{ $frame->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            @if($frame->image)
                                <img src="{{ asset($frame->image) }}" alt="Frame Image" class="img-thumbnail mt-2" style="max-width: 150px;">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="grid_columns" class="form-label">Grid Columns</label>
                            <input type="number" class="form-control" id="grid_columns" name="grid_columns" min="1" value="{{ $frame->grid_columns }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="grid_rows" class="form-label">Grid Rows</label>
                            <input type="number" class="form-control" id="grid_rows" name="grid_rows" min="1" value="{{ $frame->grid_rows }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (BDT)</label>
                            <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" value="{{ $frame->price }}" required>
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
