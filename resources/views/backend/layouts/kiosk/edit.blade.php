@extends('backend.app')
@section('title', 'Edit Kiosk')
@section('header_title', 'Edit Kiosk')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bg-white p-5">
                    <form action="{{ route('admin.kiosks.update', $kiosk->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $kiosk->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="device_id" class="form-label">Device ID</label>
                            <input type="text" class="form-control" id="device_id" name="device_id" value="{{ $kiosk->device_id }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active" {{ $kiosk->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $kiosk->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="activation_code" class="form-label">Activation Code</label>
                            <input type="text" class="form-control" id="activation_code" name="activation_code" value="{{ $kiosk->activation_code }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.kiosks.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

