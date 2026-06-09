@if(session('success'))
    <div class="mb-4 rounded-2xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 rounded-2xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
@endif
@if(session('status'))
    <div class="mb-4 rounded-2xl bg-blue-50 border border-blue-200 px-4 py-3 text-sm text-blue-800">{{ session('status') }}</div>
@endif
