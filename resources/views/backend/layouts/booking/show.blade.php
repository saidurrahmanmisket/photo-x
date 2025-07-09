@extends('backend.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Booking Details</h4>
                        <div>
                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-primary me-2">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Bookings
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Booking Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Booking ID:</strong></td>
                                    <td>#{{ $booking->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>User:</strong></td>
                                    <td>{{ $booking->user->name }} ({{ $booking->user->email }})</td>
                                </tr>
                                <tr>
                                    <td><strong>Kiosk:</strong></td>
                                    <td>{{ $booking->kiosk->name }} ({{ $booking->kiosk->device_id }})</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Type:</strong></td>
                                    <td>
                                        <span class="badge {{ $booking->payment_type === 'vendor' ? 'bg-warning' : 'bg-info' }}">
                                            {{ ucfirst($booking->payment_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td>${{ number_format($booking->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-warning',
                                                'paid' => 'bg-info',
                                                'active' => 'bg-success',
                                                'expired' => 'bg-danger',
                                                'cancelled' => 'bg-secondary'
                                            ];
                                            $color = $statusColors[$booking->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $color }}">{{ ucfirst($booking->status) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Print Information</h5>
                            <table class="table table-borderless">
                                @if($booking->payment_type === 'vendor')
                                    <tr>
                                        <td><strong>Print Limit:</strong></td>
                                        <td>{{ $booking->print_limit }} prints</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Prints Used:</strong></td>
                                        <td>{{ $booking->prints_used }} prints</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Remaining:</strong></td>
                                        <td>{{ $booking->print_limit - $booking->prints_used }} prints</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td><strong>Print Type:</strong></td>
                                        <td>Unlimited</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Prints Used:</strong></td>
                                        <td>{{ $booking->prints_used }} prints</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>{{ $booking->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @if($booking->expires_at)
                                    <tr>
                                        <td><strong>Expires At:</strong></td>
                                        <td>{{ $booking->expires_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @endif
                                @if($booking->transaction_id)
                                    <tr>
                                        <td><strong>Transaction ID:</strong></td>
                                        <td>{{ $booking->transaction_id }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($booking->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Notes</h5>
                                <div class="card">
                                    <div class="card-body">
                                        {{ $booking->notes }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($booking->photos->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Photos ({{ $booking->photos->count() }})</h5>
                                <div class="row">
                                    @foreach($booking->photos as $photo)
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="{{ asset($photo->image_path) }}" class="card-img-top" alt="Photo">
                                                <div class="card-body">
                                                    <small class="text-muted">{{ $photo->created_at->format('Y-m-d H:i') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($booking->payments->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Payments ({{ $booking->payments->count() }})</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Payment ID</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Gateway</th>
                                                <th>Transaction ID</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($booking->payments as $payment)
                                                <tr>
                                                    <td>#{{ $payment->id }}</td>
                                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                                    <td>
                                                        <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                                                            {{ ucfirst($payment->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $payment->payment_gateway }}</td>
                                                    <td>{{ $payment->transaction_id }}</td>
                                                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 