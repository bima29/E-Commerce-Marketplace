<!doctype html>
<html lang="en">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    @include('partials.navbar')

    <div class="container mx-auto px-4 py-6">
        @include('partials.flash')
        @include('partials.errors')

        @yield('content')
    </div>

    @include('partials.footer')
</body>
</html>
