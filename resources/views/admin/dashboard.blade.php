@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="fw-bold text-dark mb-0">📊 Dashboard Overview</h2>
        <button class="btn btn-primary d-flex align-items-center shadow-sm">
            <i class="fas fa-download me-2"></i> Export Report
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        @php
        // Dynamically generated cards using the data from the controller
        $cards = [
        // Total Customers (Users)
        ['title' => 'Total Customers', 'value' => $userCount, 'icon' => 'fa-users', 'color' => 'bg-primary text-white', 'link' => route('admin.users.index')],

        // Total Products
        ['title' => 'Total Products', 'value' => $productCount, 'icon' => 'fa-cubes', 'color' => 'bg-success text-white', 'link' => route('admin.products.index')],

        // Total Categories
        ['title' => 'Total Categories', 'value' => $categoryCount, 'icon' => 'fa-list-alt', 'color' => 'bg-info text-white', 'link' => route('admin.categories.index')],

        // Placeholder for Orders/Revenue (Using dummy data for now)
        ['title' => 'Total Orders', 'value' => '456', 'icon' => 'fa-shopping-bag', 'color' => 'bg-warning text-dark', 'link' => '#'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-xl-3 col-md-6">
            {{-- Card ko link se wrap kar diya --}}
            <a href="{{ $card['link'] }}" class="text-decoration-none">
                <div class="card {{ $card['color'] }} border-0 rounded-4 shadow-sm hover-lift h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-light opacity-75">{{ $card['title'] }}</small>
                            <h3 class="fw-bold mb-0">{{ $card['value'] }}</h3>
                        </div>
                        <i class="fas {{ $card['icon'] }} fs-2 opacity-75"></i>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    <!-- Chart and Orders Section -->
    <div class="row g-4">
        <!-- Sales Chart -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-semibold mb-0 text-dark">Monthly Sales Overview</h5>
                </div>
                <div class="chart-container" style="position: relative; height:400px; width:600px; margin:auto;">
                    <canvas id="salesChart"></canvas>
                </div>

            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-5    ">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0 text-dark">Recent Orders</h5>
                    <a href="#" class="text-primary small">View All</a>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach([
                    ['id' => '#1001', 'status' => 'Completed', 'badge' => 'success'],
                    ['id' => '#1002', 'status' => 'Pending', 'badge' => 'warning'],
                    ['id' => '#1003', 'status' => 'Shipped', 'badge' => 'info'],
                    ['id' => '#1004', 'status' => 'Completed', 'badge' => 'success'],
                    ['id' => '#1005', 'status' => 'Cancelled', 'badge' => 'danger'],
                    ] as $order)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">{{ $order['id'] }}</span>
                        <span class="badge bg-{{ $order['badge'] }}">{{ $order['status'] }}</span>
                    </li>
                    @endforeach
                </ul>
                <div class="card-footer bg-white border-0 text-center">
                    <button class="btn btn-outline-primary btn-sm w-100">Manage Orders</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    .hover-lift {
        transition: all 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    /* Add specific hover styles for the cards */
    .bg-primary.text-white:hover {
        background-color: #3b5998 !important;
    }

    .bg-success.text-white:hover {
        background-color: #0d995f !important;
    }

    .bg-info.text-white:hover {
        background-color: #0f93a8 !important;
    }

    .bg-warning.text-dark:hover {
        background-color: #e0ac00 !important;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js Line Chart -->
<script>
    const ctx = document.getElementById('salesChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Monthly Sales',
                data: [120, 190, 300, 500, 200],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // important!
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#333',
                        font: {
                            size: 14
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Monthly Sales Overview',
                    font: {
                        size: 18,
                        weight: 'bold'
                    },
                    color: '#111'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#444',
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    ticks: {
                        color: '#444',
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
</script>
@endpush