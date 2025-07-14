@extends('backend.app')
@section('title', 'Edit Advertisement')
@section('header_title', 'Edit Advertisement')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bg-white p-5">
                    <form action="{{ route('admin.advertisements.update', $ad->id) }}" method="POST" enctype="multipart/form-data" id="adEditForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $ad->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $ad->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $ad->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $ad->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Existing Media</label>
                            <div class="row">
                                @foreach($ad->media as $media)
                                    <div class="col-4 mb-3" id="media-{{ $media->id }}">
                                        @if($media->type === 'image')
                                            <img src="{{ asset($media->file_path) }}" class="img-fluid rounded" style="max-height:120px;">
                                        @else
                                            <video src="{{ asset($media->file_path) }}" controls style="max-width:100%; max-height:120px;"></video>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-danger mt-2 w-100" onclick="deleteMedia({{ $media->id }})">Delete</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Add More Media (Images or Videos)</label>
                            <div class="dropzone" id="media-dropzone-edit"></div>
                            <small class="text-muted">You can upload more images (jpeg, png, jpg, webp) or videos (mp4, mov, avi). Max size: 20MB each.</small>
                            @error('media')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
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
const myDropzoneEdit = new Dropzone('#media-dropzone-edit', {
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

$('#adEditForm').on('submit', function(e) {
    e.preventDefault();
    $(this).find('input[name="media[]"]').remove();
    myDropzoneEdit.files.forEach(function(file) {
        if (file.accepted) {
            const fileInput = $('<input type="file" name="media[]" style="display:none;" />')[0];
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            $('#adEditForm').append(fileInput);
        }
    });
    this.submit();
});

window.deleteMedia = function(id) {
    if (!confirm('Are you sure you want to delete this media?')) return;
    $.post("{{ route('admin.advertisements.media.destroy', ':id') }}".replace(':id', id), {_token: '{{ csrf_token() }}'}, function(resp) {
        if (resp.success) {
            $('#media-' + id).remove();
        } else {
            alert('Failed to delete media.');
        }
    });
};
</script>
@endpush 