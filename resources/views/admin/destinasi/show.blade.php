@extends('layouts.app')

@section('content')
<div class="main-content">
    <div style="max-width:800px;margin:0 auto;padding:24px;background:white;border-radius:8px;">
        <h1 class="text-2xl font-semibold mb-2">{{ $data->nama }}</h1>
        <p class="text-sm text-gray-600 mb-4"><strong>Kategori:</strong> {{ $data->kategori }}</p>
        <p class="mb-4">{{ $data->deskripsi }}</p>

        @if($data->foto)
            <img src="{{ $data->foto }}" alt="foto" style="max-width:100%" class="mb-4">
        @endif

        <div>
            <a href="{{ route('admin.destinasi.edit', $data->id) }}" class="bg-yellow-400 px-4 py-2 rounded">Edit</a>
        </div>
    </div>
</div>
@endsection
