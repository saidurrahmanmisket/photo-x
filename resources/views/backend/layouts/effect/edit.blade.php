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
                            <label for="frame_id" class="form-label">Frame (optional)</label>
                            <select class="form-control" id="frame_id" name="frame_id">
                                <option value="">None</option>
                                @foreach($frames as $frame)
                                    <option value="{{ $frame->id }}" {{ $effect->frame_id == $frame->id ? 'selected' : '' }}>{{ $frame->name }}</option>
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
