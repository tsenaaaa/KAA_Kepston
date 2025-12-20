@extends('layouts.app')

@section('content')
<div class="main-content">
    <div style="max-width:1280px;margin:0 auto;padding:24px;">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold">Destinasi</h1>
            <a href="{{ route('admin.destinasi.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-md">Tambah Destinasi</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-50 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @foreach($list as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->kategori }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->rating ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <a href="{{ route('admin.destinasi.show', $item->id) }}" class="inline-block px-3 py-1 bg-gray-100 rounded mr-2">Lihat</a>
                            <a href="{{ route('admin.destinasi.edit', $item->id) }}" class="inline-block px-3 py-1 bg-yellow-100 rounded mr-2">Edit</a>
                            <form action="{{ route('admin.destinasi.destroy', $item->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded" onclick="return confirm('Hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $list->links() }}
        </div>
    </div>
</div>
@endsection
