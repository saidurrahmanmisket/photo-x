@extends('backend.app')
@section('title', 'Add Frame')
@section('header_title', 'Add Frame')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bg-white p-5">
                    <form action="{{ route('admin.frames.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" required>
                            <small class="text-muted">Supported formats: JPEG, PNG, JPG, SVG, WebP (max 5MB)</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="grid_columns" class="form-label">Grid Columns</label>
                            <input type="number" class="form-control @error('grid_columns') is-invalid @enderror" id="grid_columns" name="grid_columns" min="1" value="{{ old('grid_columns') }}" required>
                            @error('grid_columns')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="grid_rows" class="form-label">Grid Rows</label>
                            <input type="number" class="form-control @error('grid_rows') is-invalid @enderror" id="grid_rows" name="grid_rows" min="1" value="{{ old('grid_rows') }}" required>
                            @error('grid_rows')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (BDT)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" min="0" step="0.01" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kiosk_ids" class="form-label">Kiosks</label>
                            <select class="form-select @error('kiosk_ids') is-invalid @enderror" id="kiosk_ids" name="kiosk_ids[]" multiple required>
                                <option value="all">All Kiosks</option>
                                @foreach($kiosks as $kiosk)
                                    <option value="{{ $kiosk->id }}">{{ $kiosk->name }} ({{ $kiosk->device_id }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple kiosks.</small>
                            @error('kiosk_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.frames.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
$(document).ready(function() {
    $('#kiosk_ids').select2({
        placeholder: 'Select kiosks',
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush
