@extends('layouts.app')

@section('styles')
<style>
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .main-content div[style*="max-width:1280px"] {
            padding: 16px;
        }
        .flex.items-center.justify-between.mb-6 {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .text-2xl.font-semibold {
            font-size: 1.5rem;
        }
        .bg-red-600.text-white.px-4.py-2.rounded-md {
            width: 100%;
            text-align: center;
        }
        .text-xl.font-semibold.mb-4 {
            font-size: 1.125rem;
        }
        .overflow-x-auto.bg-white.rounded.shadow table {
            font-size: 0.875rem;
        }
        .px-6.py-3 {
            padding: 0.75rem 0.5rem;
        }
        .px-6.py-4 {
            padding: 0.5rem 0.5rem;
        }
        .flex.flex-wrap.gap-1 {
            flex-direction: column;
            gap: 0.25rem;
        }
        .inline-block.px-2.py-1 {
            width: 100%;
            text-align: center;
            padding: 0.375rem 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .main-content div[style*="max-width:1280px"] {
            padding: 12px;
        }
        .text-2xl.font-semibold {
            font-size: 1.25rem;
        }
        .text-xl.font-semibold.mb-4 {
            font-size: 1rem;
        }
        .overflow-x-auto.bg-white.rounded.shadow table {
            font-size: 0.75rem;
        }
        .px-6.py-3 {
            padding: 0.5rem 0.375rem;
        }
        .px-6.py-4 {
            padding: 0.375rem 0.375rem;
        }
        .inline-block.px-2.py-1 {
            padding: 0.25rem 0.375rem;
            font-size: 0.75rem;
        }
    }
</style>
@endsection

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

        @php
            $grouped = $list->groupBy('kategori');
            $categories = ['Culinary', 'Tourism', 'Shopping'];
        @endphp

        @foreach($categories as $category)
            @if(isset($grouped[$category]) && $grouped[$category]->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">
                        {{ $category }} ({{ $grouped[$category]->count() }} destinasi)
                    </h2>

                    <div class="overflow-x-auto bg-white rounded shadow">
                        <table class="w-full divide-y" style="table-layout: fixed;">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 50%;">Nama</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 30%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y">
                                @foreach($grouped[$category] as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 break-words overflow-hidden" style="word-wrap: break-word;">{{ $item->nama }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 text-center">{{ $item->rating ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="flex flex-wrap gap-1">
                                            <a href="{{ route('admin.destinasi.show', $item->id) }}" class="inline-block px-2 py-1 bg-gray-100 rounded text-xs whitespace-nowrap">Lihat</a>
                                            <a href="{{ route('admin.destinasi.edit', $item->id) }}" class="inline-block px-2 py-1 bg-yellow-100 rounded text-xs whitespace-nowrap">Edit</a>
                                            <form action="{{ route('admin.destinasi.destroy', $item->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="inline-block px-2 py-1 bg-red-100 text-red-700 rounded text-xs whitespace-nowrap" onclick="return confirm('Hapus?')">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endforeach

        @if($list->isEmpty())
            <div class="bg-white rounded shadow p-8 text-center">
                <p class="text-gray-500">Belum ada destinasi yang ditambahkan.</p>
            </div>
        @endif
    </div>
</div>
@endsection
