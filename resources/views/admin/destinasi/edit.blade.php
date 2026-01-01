@extends('layouts.app')

@section('styles')
<style>
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .main-content div[style*="max-width:720px"] {
            padding: 16px;
            margin: 0 8px;
        }
        .text-xl.font-semibold {
            font-size: 1.25rem;
        }
        .mb-4 {
            margin-bottom: 1rem;
        }
        .block.text-sm.font-medium {
            font-size: 0.875rem;
        }
        .mt-1.block.w-full {
            font-size: 16px; /* Prevent zoom on iOS */
        }
        .bg-red-600.text-white.px-4.py-2 {
            width: 100%;
            padding: 0.75rem 1rem;
        }
        div.mb-2 img {
            max-width: 150px;
        }
    }

    @media (max-width: 480px) {
        .main-content div[style*="max-width:720px"] {
            padding: 12px;
            margin: 0 4px;
        }
        .text-xl.font-semibold {
            font-size: 1.125rem;
        }
        .mb-4 {
            margin-bottom: 0.75rem;
        }
        .block.text-sm.font-medium {
            font-size: 0.8125rem;
        }
        .mt-1.block.w-full {
            padding: 0.5rem;
        }
        .bg-red-600.text-white.px-4.py-2 {
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
        }
        div.mb-2 img {
            max-width: 120px;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <div style="max-width:720px;margin:0 auto;padding:24px;background:white;border-radius:8px;">
        <h1 class="text-xl font-semibold mb-4">Edit Destinasi</h1>

        @if($errors->any())
            <div class="mb-4 p-3 rounded bg-red-50 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.destinasi.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input name="nama" class="mt-1 block w-full rounded border-gray-200" value="{{ old('nama', $data->nama) }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" class="mt-1 block w-full rounded border-gray-200">{{ old('deskripsi', $data->deskripsi) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <input name="alamat" class="mt-1 block w-full rounded border-gray-200" value="{{ old('alamat', $data->alamat) }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="kategori" class="mt-1 block w-full rounded border-gray-200" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Culinary" {{ old('kategori', $data->kategori) === 'Culinary' ? 'selected' : '' }}>Culinary (Resto, Cafe, dll)</option>
                    <option value="Tourism" {{ old('kategori', $data->kategori) === 'Tourism' ? 'selected' : '' }}>Tourism (Museum, Tempat Ibadah, Public Space, Hotel, dll)</option>
                    <option value="Shopping" {{ old('kategori', $data->kategori) === 'Shopping' ? 'selected' : '' }}>Shopping (Pasar, Mall, Swalayan, dll)</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Foto (unggah)</label>
                @if($data->foto)
                    <div class="mb-2"><img src="{{ $data->foto }}" style="max-width:200px"></div>
                @endif
                <input type="file" name="foto" class="mt-1 block w-full">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">TikTok (URL)</label>
                <input name="tiktok" class="mt-1 block w-full rounded border-gray-200" value="{{ old('tiktok', $data->tiktok) }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Rating</label>
                <input name="rating" class="mt-1 block w-full rounded border-gray-200" value="{{ old('rating', $data->rating) }}">
            </div>

            <div class="flex justify-end">
                <button class="bg-red-600 text-white px-4 py-2 rounded">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
