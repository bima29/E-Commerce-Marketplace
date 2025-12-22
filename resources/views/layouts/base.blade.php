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

        <div class="grid grid-cols-12 gap-6">
            <aside class="col-span-12 md:col-span-3">
                @yield('sidebar')
            </aside>

            <main class="col-span-12 md:col-span-9">
                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
