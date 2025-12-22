@if ($errors->any())
    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
        <div class="font-semibold mb-2">Terjadi kesalahan:</div>
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
