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
                            <input type="text" class="form-control" id="name" name="name" value="{{ $effect->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            @if($effect->image)
                                <img src="{{ asset($effect->image) }}" alt="Effect Image" class="img-thumbnail mt-2" style="max-width: 150px;">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="frame_ids" class="form-label">Frames (optional, multi-select)</label>
                            <select class="form-control select2" id="frame_ids" name="frame_ids[]" multiple>
                                <option value="all" {{ count($selectedFrames ?? []) === $frames->count() ? 'selected' : '' }}>All</option>
                                @foreach($frames as $frame)
                                    <option value="{{ $frame->id }}" {{ in_array($frame->id, $selectedFrames ?? []) ? 'selected' : '' }}>{{ $frame->name }}</option>
                                @endforeach
                            </select>
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
        allowClear: true
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
