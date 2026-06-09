@extends('layouts.dashboard')
@section('title', 'Kelola Venues')
@section('header', 'Kelola Venues')

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <p class="text-gray-500">Kelola semua lapangan di platform</p>
    <a href="{{ route('admin.venues.create') }}" class="btn-secondary">+ Tambah Venue</a>
</div>

<div class="card-soft overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3 font-medium">Nama</th>
                    <th class="px-5 py-3 font-medium">Olahraga</th>
                    <th class="px-5 py-3 font-medium">Kota</th>
                    <th class="px-5 py-3 font-medium">Pemilik</th>
                    <th class="px-5 py-3 font-medium">Harga</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($venues as $venue)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-5 py-4 font-medium">{{ $venue->name }}</td>
                        <td class="px-5 py-4">{{ $venue->sport->name }}</td>
                        <td class="px-5 py-4">{{ $venue->city }}</td>
                        <td class="px-5 py-4">{{ $venue->owner->name }}</td>
                        <td class="px-5 py-4">{{ $venue->formattedPrice() }}/jam</td>
                        <td class="px-5 py-4">
                            <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $venue->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $venue->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            @if($venue->is_featured)
                                <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 ml-1">Featured</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right space-x-3">
                            <a href="{{ route('admin.venues.edit', $venue) }}" class="text-secondary font-medium hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.venues.destroy', $venue) }}" class="inline"
                                  onsubmit="return confirm('Hapus venue ini? Semua booking terkait juga akan dihapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 font-medium hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-gray-500">Belum ada venue.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $venues->links() }}</div>
@endsection
