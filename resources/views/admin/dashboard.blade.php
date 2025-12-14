@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
<!-- Chart.js is loaded via CDN in scripts; styles for cards -->
<style>
    :root {
        --primary: #DB4444;
        --primary-light: #ff6969;
        --muted: #6b7280;
        --card-bg: #FCF8F8;
        --shadow: rgba(0, 0, 0, 0.06);
    }

    /* Page layout tweaks */
    .dashboard-cards {
        gap: 1rem;
    }

    .card-ghost {
        background: var(--card-bg);
        border-radius: 12px;
        box-shadow: 0 6px 18px var(--shadow);
        border: 1px solid #f0f0f0;
        padding: 18px;
    }

    /* Small stat title */
    .stat-title {
        font-size: 13px;
        color: var(--muted);
    }

    /* Big stat number */
    .stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #111;
    }

    /* Primary accent */
    .accent {
        color: var(--primary);
    }

    .chart-card {
        padding: 16px;
        border-radius: 12px;
        background: var(--card-bg);
        box-shadow: 0 6px 18px var(--shadow);
        border: 1px solid #f0f0f0;
        height: 100%;
    }

    /* Canvas fix for responsiveness */
    #ordersRevenueChart {
        width: 100% !important;
        height: 100% !important;
        max-height: 380px !important;
    }

    @media (max-width: 991px) {
        #ordersRevenueChart {
            max-height: 300px !important;
        }
    }

    @media (max-width: 576px) {
        #ordersRevenueChart {
            max-height: 250px !important;
        }
    }


    /* Recent orders list */
    .list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 14px;
        border-bottom: 1px solid #f5f5f5;
    }

    .list-item .left {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    /* status badge colors use bootstrap classes - ok */

    /* small helper */
    .small-muted {
        color: #6b7280;
        font-size: 13px;
    }

    .icon-badge {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(219, 68, 68, 0.12);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .icon-badge i {
        font-size: 22px;
        color: var(--primary);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="fw-bold text-dark mb-0">📊 Dashboard Overview</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger d-flex align-items-center shadow-sm">
            <i class="fas fa-box me-2"></i> View Products
        </a>
    </div>

    <!-- Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card-ghost d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-title">Total Customers</div>
                    <div class="stat-value">{{ number_format($userCount) }}</div>
                    <div class="small-muted">Registered users</div>
                </div>
                <div class="icon-badge">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card-ghost d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-title">Total Products</div>
                    <div class="stat-value">{{ number_format($productCount) }}</div>
                    <div class="small-muted">Active products</div>
                </div>
                <div class="icon-badge">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card-ghost d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-title">Total Categories</div>
                    <div class="stat-value">{{ number_format($categoryCount) }}</div>
                    <div class="small-muted">Product categories</div>
                </div>
                <div class="icon-badge">
                    <i class="fas fa-list-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card-ghost d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-title">Orders (Total)</div>
                    <div class="stat-value">{{ number_format($orderCount) }}</div>
                    <div class="small-muted">Total orders</div>
                </div>
                <div class="icon-badge">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Chart + Recent Orders -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-0">Monthly Orders & Revenue ({{ $currentYear }})</h5>
                        <small class="small-muted">Orders (bar) vs Revenue (line)</small>
                    </div>
                    <div class="small-muted">Total Revenue: <strong class="accent">₹{{ number_format($totalRevenue,2) }}</strong></div>
                </div>

                <canvas id="ordersRevenueChart"></canvas>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-ghost">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Recent Orders</h6>
                    <a href="{{ route('admin.orders.index') }}" class="small-muted">View all</a>
                </div>

                <div>
                    @forelse($recentOrders as $order)
                    <div class="list-item">
                        <div class="left">
                            <div>
                                <div style="font-weight:700;">{{ $order->order_number ?? '#'.$order->id }}</div>
                                <div class="small-muted">{{ $order->user->name ?? 'Guest' }}</div>
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="small-muted">₹{{ number_format($order->total_amount,2) }}</div>
                            @php
                            $badge = match($order->status) {
                            'completed' => 'success',
                            'shipped' => 'info',
                            'processing' => 'primary',
                            'pending' => 'warning',
                            'cancelled' => 'danger',
                            'refunded' => 'dark',
                            default => 'secondary',
                            };
                            @endphp
                            <div class="mt-1"><span class="badge bg-{{ $badge }}">{{ ucfirst($order->status) }}</span></div>
                        </div>
                    </div>
                    @empty
                    <div class="p-3 small-muted">No recent orders</div>
                    @endforelse
                </div>

                <div class="mt-3 small-muted">Orders by status</div>
                <div class="mt-2">
                    @foreach($ordersByStatus as $status => $count)
                    <div class="d-flex justify-content-between small-muted">
                        <div>{{ ucfirst($status) }}</div>
                        <div><strong>{{ $count }}</strong></div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@php
$monthsJson = json_encode($months);
$ordersJson = json_encode($ordersByMonth);
$revenueJson = json_encode($revenueByMonth);
@endphp
@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const months = JSON.parse('{!! $monthsJson !!}');
    const ordersData = JSON.parse('{!! $ordersJson !!}');
    const revenueData = JSON.parse('{!! $revenueJson !!}');
    const ctx = document.getElementById('ordersRevenueChart').getContext('2d');

    const ordersRevenueChart = new Chart(ctx, {
        data: {
            labels: months,
            datasets: [{
                    type: 'bar',
                    label: 'Orders',
                    data: ordersData,
                    backgroundColor: 'rgba(219,68,68,0.9)', // red
                    borderRadius: 6,
                    yAxisID: 'y',
                },
                {
                    type: 'line',
                    label: 'Revenue (₹)',
                    data: revenueData,
                    borderColor: '#DB4444',
                    backgroundColor: 'rgba(219,68,68,0.12)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#DB4444',
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#111'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (context.dataset.type === 'line') {
                                return label + ': ₹' + Number(context.parsed.y).toLocaleString();
                            }
                            return label + ': ' + Number(context.parsed.y).toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        color: '#444'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.03)'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        color: '#444',
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    ticks: {
                        color: '#444'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush