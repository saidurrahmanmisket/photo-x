@extends('backend.app')
@section('title', 'Add Kiosk')
@section('header_title', 'Add Kiosk')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="bg-white p-5">
                    <form action="{{ route('admin.kiosks.store') }}" method="POST">
                        @csrf
              <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required value="{{ old('name') }}">
                  @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="mb-3">
                  <label for="device_id" class="form-label">Device ID</label>
                  <input type="text" class="form-control @error('device_id') is-invalid @enderror" id="device_id" name="device_id" required value="{{ old('device_id') }}">
                  @error('device_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="mb-3">
                  <label for="status" class="form-label">Status</label>
                  <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                      <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                      <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                  </select>
                  @error('status')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="mb-3">
                  <label for="activation_code" class="form-label">Activation Code</label>
                  <input type="text" class="form-control @error('activation_code') is-invalid @enderror" id="activation_code" name="activation_code" value="{{ old('activation_code') }}">
                  @error('activation_code')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="mb-3">
                  <label for="max_clicks" class="form-label">Maximum Clicks</label>
                  <input type="number" class="form-control @error('max_clicks') is-invalid @enderror" id="max_clicks" name="max_clicks" min="1" value="{{ old('max_clicks') }}">
                  <small class="text-muted">Maximum number of clicks allowed for this kiosk (optional)</small>
                  @error('max_clicks')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="mb-3">
                  <label for="max_capture_seconds" class="form-label">Maximum Seconds for Capture</label>
                  <input type="number" class="form-control @error('max_capture_seconds') is-invalid @enderror" id="max_capture_seconds" name="max_capture_seconds" min="1" value="{{ old('max_capture_seconds') }}">
                  <small class="text-muted">Maximum seconds allowed for capturing an image (optional)</small>
                  @error('max_capture_seconds')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="mb-3">
                  <label for="grid_option" class="form-label">Grid Option</label>
                  <select class="form-control @error('grid_option') is-invalid @enderror" id="grid_option" name="grid_option" required>
                      <option value="">Select Grid</option>
                      <option value="2x2" {{ old('grid_option') == '2x2' ? 'selected' : '' }}>2 x 2</option>
                      <option value="3x2" {{ old('grid_option') == '3x2' ? 'selected' : '' }}>3 x 2</option>
                      <option value="1x1" {{ old('grid_option') == '1x1' ? 'selected' : '' }}>1 x 1</option>
                      <option value="3x3" {{ old('grid_option') == '3x3' ? 'selected' : '' }}>3 x 3</option>
                  </select>
                  @error('grid_option')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.kiosks.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

