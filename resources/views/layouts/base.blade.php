<!doctype html>
<html lang="en">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        @include('partials.navbar')

        <main class="flex-1">
            <div class="container mx-auto px-4 lg:px-8 py-6 md:py-8">
                @include('partials.flash')
                @include('partials.errors')

                <div class="grid grid-cols-12 gap-6 lg:gap-8">
                    <aside class="col-span-12 md:col-span-4 lg:col-span-3 md:sticky md:top-24 self-start">
                        <div class="space-y-4">
                            @yield('sidebar')
                        </div>
                    </aside>

                    <main class="col-span-12 md:col-span-8 lg:col-span-9">
                        @yield('content')
                    </main>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </div>

    @stack('scripts')
</body>
</html>
