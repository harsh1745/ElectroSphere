<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>

    {{-- Bootstrap CSS include --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    {{-- Agar koi custom style login page ke liye hai toh yahan aayega --}}
    @stack('styles') 
    
    <style>
        /* Body style for a clean, centered look on auth pages */
        body {
            margin: 0;
            background-color: #f8f9fa; /* Login page background color */
        }
    </style>
</head>

<body>
    {{-- Yahaan par 'content' section ka data aayega (jo ki aapka login form hai) --}}
    @yield('content') 

    {{-- Bootstrap JS include --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>