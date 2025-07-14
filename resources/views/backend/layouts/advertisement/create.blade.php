@extends('backend.app')
@section('title', 'Add Advertisement')
@section('header_title', 'Add Advertisement')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bg-white p-5">
                    <form action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data" id="adForm">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Media (Images or Videos)</label>
                            <div class="dropzone" id="media-dropzone"></div>
                            <small class="text-muted">You can upload multiple images (jpeg, png, jpg, webp) or videos (mp4, mov, avi). Max size: 20MB each.</small>
                            @error('media')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
Dropzone.autoDiscover = false;
const myDropzone = new Dropzone('#media-dropzone', {
    url: '#', // Prevent auto-upload
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 10,
    maxFiles: 10,
    maxFilesize: 20, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.webp,.mp4,.mov,.avi',
    addRemoveLinks: true,
    dictDefaultMessage: 'Drag & drop files here or click to select',
});

// On form submit, append files to the form and submit
$('#adForm').on('submit', function(e) {
    e.preventDefault();
    // Remove any previous file inputs
    $(this).find('input[name="media[]"]').remove();
    // Append files from Dropzone to the form
    myDropzone.files.forEach(function(file) {
        if (file.accepted) {
            const fileInput = $('<input type="file" name="media[]" style="display:none;" />')[0];
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            $('#adForm').append(fileInput);
        }
    });
    // Now submit the form
    this.submit();
});
</script>
@endpush 