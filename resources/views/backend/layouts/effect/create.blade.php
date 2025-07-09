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
                            <label for="frame_id" class="form-label">Frame (optional)</label>
                            <select class="form-control" id="frame_id" name="frame_id">
                                <option value="">None</option>
                                {{-- @foreach($frames as $frame)
                                    <option value="{{ $frame->id }}">{{ $frame->name }}</option>
                                @endforeach --}}
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
