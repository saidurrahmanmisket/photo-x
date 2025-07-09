@extends('backend.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Booking Settings</h4>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Bookings
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.settings.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendor_print_limit" class="form-label">Default Vendor Print Limit <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('vendor_print_limit') is-invalid @enderror" 
                                           id="vendor_print_limit" name="vendor_print_limit" 
                                           value="{{ old('vendor_print_limit', $settings['vendor_print_limit']) }}" 
                                           min="1" max="100" required>
                                    <small class="text-muted">Default number of prints allowed for vendor payment type bookings</small>
                                    @error('vendor_print_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_booking_duration" class="form-label">Default Booking Duration (Hours) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('default_booking_duration') is-invalid @enderror" 
                                           id="default_booking_duration" name="default_booking_duration" 
                                           value="{{ old('default_booking_duration', $settings['default_booking_duration']) }}" 
                                           min="1" max="168" required>
                                    <small class="text-muted">Default duration in hours for bookings (max 7 days)</small>
                                    @error('default_booking_duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_prints_per_booking" class="form-label">Maximum Prints Per Booking <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_prints_per_booking') is-invalid @enderror" 
                                           id="max_prints_per_booking" name="max_prints_per_booking" 
                                           value="{{ old('max_prints_per_booking', $settings['max_prints_per_booking']) }}" 
                                           min="1" max="1000" required>
                                    <small class="text-muted">Maximum number of prints allowed per booking</small>
                                    @error('max_prints_per_booking')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 