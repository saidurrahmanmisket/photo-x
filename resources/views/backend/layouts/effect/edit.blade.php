@extends('backend.app')
@section('title', 'Edit Effect')
@section('header_title', 'Edit Effect')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bg-white p-5">
                    <form action="{{ route('admin.effects.update', $effect->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $effect->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            <small class="text-muted">Supported formats: JPEG, PNG, JPG, SVG (max 5MB)</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($effect->image)
                                <img src="{{ asset($effect->image) }}" alt="Effect Image" class="img-thumbnail mt-2" style="max-width: 150px;">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="frame_ids" class="form-label">Frames (optional, multi-select)</label>
                            <select class="form-control select2 @error('frame_ids') is-invalid @enderror" id="frame_ids" name="frame_ids[]" multiple>
                                <option value="all" {{ count($selectedFrames ?? []) === $frames->count() ? 'selected' : '' }}>All Frames</option>
                                @foreach($frames as $frame)
                                    <option value="{{ $frame->id }}" {{ in_array($frame->id, old('frame_ids', $selectedFrames ?? [])) ? 'selected' : '' }}>{{ $frame->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select frames this effect can be applied to. Choose "All Frames" to apply to all frames.</small>
                            @error('frame_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.effects.index') }}" class="btn btn-secondary">Cancel</a>
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
    $('#frame_ids').select2({
        placeholder: 'Select frames (optional)',
        allowClear: true,
        width: '100%'
    });
    
    $('#frame_ids').on('change', function() {
        if ($(this).val() && $(this).val().includes('all')) {
            // Select all except 'all'
            let allValues = @json($frames->pluck('id')->map(fn($id) => (string)$id)->toArray());
            $(this).val(['all', ...allValues]).trigger('change.select2');
        }
    });
});
</script>
@endpush
