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
        <h2 class="fw-bold text-dark mb-0"> Dashboard Overview</h2>
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


    <!-- ================= ROW 1 : TWO CHARTS SIDE BY SIDE ================= -->
    <div class="row g-4">

        <!-- MONTHLY CHART -->
        <div class="col-lg-6 col-md-12">
            <div class="chart-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>
                        <h6 class="mb-0">Monthly Orders & Revenue ({{ $selectedYear }})</h6>

                        <small class="small-muted">Orders vs Revenue</small>
                        <small class="small-muted">Year-wise data</small>
                    </div>
                    <!-- YEAR DROPDOWN -->
                    <form method="GET">
                        <select name="year"
                            class="form-select form-select-sm"
                            onchange="this.form.submit()">
                            @for($year = 2025; $year <= 2030; $year++)
                                <option value="{{ $year }}"
                                {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                                </option>
                                @endfor
                        </select>
                    </form>
                    <div class="small-muted">
                        <strong class="accent">₹{{ number_format($totalRevenue,2) }}</strong>
                    </div>
                </div>

                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- DAILY CHART -->
        <div class="col-lg-6 col-md-12">
            <div class="chart-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-0">Daily Orders & Revenue ({{ now()->format('F Y') }})</h6>
                        <small class="small-muted">Day-wise performance</small>
                    </div>
                </div>

                <canvas id="dailyChart"></canvas>
            </div>
        </div>

    </div>

    <!-- ================= ROW 2 : RECENT ORDERS (FULL WIDTH) ================= -->
    <div class="row g-4 mt-1">

        <div class="col-12">
            <div class="card-ghost">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Recent Orders</h6>
                    <a href="{{ route('admin.orders.index') }}" class="small-muted">View all</a>
                </div>

                <div>
                    @forelse($recentOrders as $order)
                    <div class="list-item">
                        <div>
                            <div class="fw-bold">
                                {{ $order->order_number ?? '#'.$order->id }}
                            </div>
                            <div class="small-muted">
                                {{ $order->user->name ?? 'Guest' }}
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="small-muted">
                                ₹{{ number_format($order->total_amount,2) }}
                            </div>
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
                            <span class="badge bg-{{ $badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
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
$ordersMonthJson = json_encode($ordersByMonth);
$revenueMonthJson = json_encode($revenueByMonth);

$daysJson = json_encode($days);
$ordersDayJson = json_encode($ordersByDay);
$revenueDayJson = json_encode($revenueByDay);
@endphp


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // =======================
    // DATA
    // =======================
    const months = JSON.parse('{!! $monthsJson !!}');
    const ordersMonth = JSON.parse('{!! $ordersMonthJson !!}');
    const revenueMonth = JSON.parse('{!! $revenueMonthJson !!}');

    const days = JSON.parse('{!! $daysJson !!}');
    const ordersDay = JSON.parse('{!! $ordersDayJson !!}');
    const revenueDay = JSON.parse('{!! $revenueDayJson !!}');

    // =======================
    // COMMON OPTIONS
    // =======================
    function chartOptions() {
        return {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const label = ctx.dataset.label;
                            const val = ctx.parsed.y.toLocaleString();
                            return label.includes('Revenue') ?
                                `${label}: ₹${val}` :
                                `${label}: ${val}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        callback: v => '₹' + v.toLocaleString()
                    }
                }
            }
        };
    }

    // =======================
    // MONTHLY CHART ✅
    // =======================
    new Chart(document.getElementById('monthlyChart'), {
        data: {
            labels: months,
            datasets: [{
                    type: 'bar',
                    label: 'Orders',
                    data: ordersMonth,
                    backgroundColor: 'rgba(219,68,68,0.9)',
                    borderRadius: 6,
                    yAxisID: 'y'
                },
                {
                    type: 'line',
                    label: 'Revenue (₹)',
                    data: revenueMonth,
                    borderColor: '#DB4444',
                    backgroundColor: 'rgba(219,68,68,0.12)',
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'y1'
                }
            ]
        },
        options: chartOptions()
    });

    // =======================
    // DAILY CHART ✅
    // =======================
    new Chart(document.getElementById('dailyChart'), {
        data: {
            labels: days,
            datasets: [{
                    type: 'bar',
                    label: 'Orders',
                    data: ordersDay,
                    backgroundColor: 'rgba(219,68,68,0.85)',
                    borderRadius: 6,
                    yAxisID: 'y'
                },
                {
                    type: 'line',
                    label: 'Revenue (₹)',
                    data: revenueDay,
                    borderColor: '#DB4444',
                    backgroundColor: 'rgba(219,68,68,0.12)',
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'y1'
                }
            ]
        },
        options: chartOptions()
    });
</script>
@endpush