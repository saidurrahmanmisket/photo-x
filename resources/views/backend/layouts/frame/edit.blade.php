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
                            <label for="grid_option" class="form-label">Grid Layout</label>
                            <select class="form-control @error('grid_option') is-invalid @enderror" id="grid_option" name="grid_option" required>
                                <option value="">Select Grid Layout</option>
                                @foreach($gridOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('grid_option', $selectedGrid) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('grid_option')
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
                        <div class="mb-3">
                            <label for="kiosk_ids" class="form-label">Kiosks</label>
                            <select class="form-select @error('kiosk_ids') is-invalid @enderror" id="kiosk_ids" name="kiosk_ids[]" multiple required>
                                <option value="all" {{ count($selectedKiosks) === $kiosks->count() ? 'selected' : '' }}>All Kiosks</option>
                                @foreach($kiosks as $kiosk)
                                    <option value="{{ $kiosk->id }}" {{ in_array($kiosk->id, old('kiosk_ids', $selectedKiosks)) ? 'selected' : '' }}>{{ $kiosk->name }} ({{ $kiosk->device_id }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple kiosks.</small>
                            @error('kiosk_ids')
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
