@if (session('success'))
    <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-800 shadow-sm">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
        {{ session('error') }}
    </div>
@endif
