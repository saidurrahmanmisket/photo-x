@extends('backend.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Edit Booking</h4>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Bookings
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('user_id', $booking->user_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} ({{ $customer->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kiosk_id" class="form-label">Kiosk <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kiosk_id') is-invalid @enderror" id="kiosk_id" name="kiosk_id" required>
                                        <option value="">Select Kiosk</option>
                                        @foreach($kiosks as $kiosk)
                                            <option value="{{ $kiosk->id }}" {{ old('kiosk_id', $booking->kiosk_id) == $kiosk->id ? 'selected' : '' }}>
                                                {{ $kiosk->name }} ({{ $kiosk->device_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kiosk_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_type" class="form-label">Payment Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_type') is-invalid @enderror" id="payment_type" name="payment_type" required>
                                        <option value="">Select Payment Type</option>
                                        <option value="vendor" {{ old('payment_type', $booking->payment_type) == 'vendor' ? 'selected' : '' }}>Vendor (Limited Prints)</option>
                                        <option value="user" {{ old('payment_type', $booking->payment_type) == 'user' ? 'selected' : '' }}>User (Fixed Amount)</option>
                                    </select>
                                    @error('payment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" name="amount" value="{{ old('amount', $booking->amount) }}" required>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3" id="print_limit_div" style="display: none;">
                                    <label for="print_limit" class="form-label">Print Limit <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('print_limit') is-invalid @enderror" 
                                           id="print_limit" name="print_limit" value="{{ old('print_limit', $booking->print_limit ?? $vendorLimit) }}" min="1">
                                    <small class="text-muted">Default: {{ $vendorLimit }} prints</small>
                                    @error('print_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ old('status', $booking->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="active" {{ old('status', $booking->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="expired" {{ old('status', $booking->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                        <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expires_at" class="form-label">Expires At</label>
                                    <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                           id="expires_at" name="expires_at" value="{{ old('expires_at', $booking->expires_at ? $booking->expires_at->format('Y-m-d\TH:i') : '') }}">
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Prints Used</label>
                                    <input type="text" class="form-control" value="{{ $booking->prints_used }}" readonly>
                                    <small class="text-muted">This field is read-only</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes', $booking->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide print limit based on payment type
    $('#payment_type').change(function() {
        if ($(this).val() === 'vendor') {
            $('#print_limit_div').show();
            $('#print_limit').prop('required', true);
        } else {
            $('#print_limit_div').hide();
            $('#print_limit').prop('required', false);
        }
    });
    
    // Trigger change event on page load
    $('#payment_type').trigger('change');
});
</script>
@endpush 