<!doctype html>
<html lang="en">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        @include('partials.navbar')

        <main class="flex-1">
            <div class="container mx-auto px-4 lg:px-8 py-6">
                @include('partials.flash')
                @include('partials.errors')

                @yield('content')
            </div>
        </main>

        @include('partials.footer')
    </div>

    @stack('scripts')
</body>
</html>
