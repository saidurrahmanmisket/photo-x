@extends('backend.app')
@section('title', 'Dashboard')
@section('header_title', 'Dashboard')
@section('content')
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3 mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fs-2hx fw-bold text-primary me-2 lh-1">{{ $totalUsers }}</h1>
                                <h4 class="fs-5 fw-semibold text-muted mt-2">Total Users</h4>
                            </div>
                            <div>
                                <i class="bi bi-people-fill display-4 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fs-2hx fw-bold text-success me-2 lh-1">{{ $totalKiosks }}</h1>
                                <h4 class="fs-5 fw-semibold text-muted mt-2">Total Kiosks</h4>
                            </div>
                            <div>
                                <i class="bi bi-question-circle-fill display-4 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fs-2hx fw-bold text-info me-2 lh-1">{{ $totalFrames }}</h1>
                                <h4 class="fs-5 fw-semibold text-muted mt-2">Total Frames</h4>
                            </div>
                            <div>
                                <i class="bi bi-file-earmark-richtext-fill display-4 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Visits Card -->
                <div class="col-12 col-md-4 col-lg-3 mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fs-2hx fw-bold text-warning me-2 lh-1">{{ $totalPhotos }}</h1>
                                <h4 class="fs-5 fw-semibold text-muted mt-2">Total Photos</h4>
                            </div>
                            <div>
                                <i class="bi bi-bar-chart-fill display-4 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="text-lg font-bold mb-4">Last 7 Days Photos Printed</h2>
                            <canvas id="visitorsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        const ctx = document.getElementById('visitorsChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Photos Printed',
                    data: @json($data),
                    fill: true,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
