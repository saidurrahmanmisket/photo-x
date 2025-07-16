@extends('backend.app')
@section('title', 'Add Effect')
@section('header_title', 'Add Effect')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bg-white p-5">
                    <form action="{{ route('admin.effects.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <div class="mb-3">
                            <label for="frame_ids" class="form-label">Frames (optional, multi-select)</label>
                            <select class="form-control select2" id="frame_ids" name="frame_ids[]" multiple>
                                <option value="all">All</option>
                                @foreach($frames as $frame)
                                    <option value="{{ $frame->id }}">{{ $frame->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
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
