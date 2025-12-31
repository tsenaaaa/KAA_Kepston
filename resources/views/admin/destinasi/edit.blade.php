@extends('layouts.app')

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
                <input name="kategori" class="mt-1 block w-full rounded border-gray-200" value="{{ old('kategori', $data->kategori) }}">
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
