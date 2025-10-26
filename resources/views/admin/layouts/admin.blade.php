<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/chart.js') }}"></script>

    <style>
        /* Basic Admin Styles for layout */
        body {
            margin: 0;
            background-color: #f4f6f9;
        }

        #wrapper {
            display: flex;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }

        #page-content-wrapper {
            flex-grow: 1;
            padding: 20px;
        }

        .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
            text-align: center;
            border-bottom: 1px solid #495057;
            margin-bottom: 15px;
        }

        .list-group-item {
            background-color: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 15px;
        }

        .list-group-item:hover,
        .list-group-item.active {
            background-color: #495057;
            color: white;
        }

        .top-bar {
            background-color: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
    </style>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    @endpush
    @stack('styles')

</head>

<body>
    <div id="wrapper">

        <div id="sidebar-wrapper">
            <div class="sidebar-heading">E-commerce Admin</div>

            <div class="list-group list-group-flush">

                {{-- 1. Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="list-group-item list-group-item-action {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>

                {{-- 2. Products --}}
                <a href="{{ route('admin.products.index') }}"
                    class="list-group-item list-group-item-action {{ Request::routeIs('admin.products.*') ? 'active' : '' }}">
                    Products
                </a>

                {{-- 3. Categories --}}
                <a href="{{ route('admin.categories.index') }}"
                    class="list-group-item list-group-item-action {{ Request::routeIs('admin.categories.*') ? 'active' : '' }}">
                    Categories
                </a>

                {{-- 4. Orders --}}
                <a href="#" class="list-group-item list-group-item-action">
                    Orders
                </a>

                {{-- 5. Reviews --}}
                <a href="#" class="list-group-item list-group-item-action">
                    Reviews
                </a>

                {{-- 6. Admins (Users) --}}
                <a href="{{ route('admin.users.index') }}"
                    class="list-group-item list-group-item-action {{ Request::routeIs('admin.users.*') ? 'active' : '' }}">
                    Users
                </a>


                {{-- Logout Button --}}
                <a href="{{ route('admin.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="list-group-item list-group-item-action" style="margin-top: 20px;">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
        <div id="page-content-wrapper">

            <div class="top-bar">
                <span>Welcome, Admin!</span>
            </div>

            <div class="container-fluid">
                {{-- Yahaan par har page ka content aayega --}}
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>