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
                <label class="block text-sm font-medium text-gray-700">Upload CSV (opsional) - rating & jumlah review</label>
                <div class="text-sm text-gray-600 mb-2">CSV hanya akan mengambil nilai dari kolom <strong>total score</strong> dan <strong>reviews count</strong> (header case-insensitive).</div>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input id="csv_file_input" type="file" name="csv_file" accept=".csv,text/csv" class="mt-1 block w-full">
                    <button type="button" id="csv_preview_btn" class="bg-gray-200 px-3 py-1 rounded">Preview</button>
                </div>
                <div id="csv-preview-box" class="mt-2 p-3 bg-gray-50 border rounded" style="display:none;max-height:260px;overflow:auto"></div>
            </div>

            <div class="flex justify-end">
                <button class="bg-red-600 text-white px-4 py-2 rounded">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
        <script>
            (function(){
                const previewBtn = document.getElementById('csv_preview_btn');
                const fileInput = document.getElementById('csv_file_input');
                const box = document.getElementById('csv-preview-box');
                if(previewBtn){
                    previewBtn.addEventListener('click', async function(){
                        if(!fileInput.files || !fileInput.files.length){
                            alert('Pilih file CSV terlebih dahulu');
                            return;
                        }

                        const fd = new FormData();
                        fd.append('csv_file', fileInput.files[0]);
                        fd.append('_token', '{{ csrf_token() }}');

                        previewBtn.disabled = true;
                        previewBtn.textContent = 'Memeriksa...';
                        try{
                            const res = await fetch('{{ route("admin.destinasi.csvPreview") }}', {method: 'POST', body: fd});
                            if(!res.ok) throw new Error('Gagal memproses');
                            const data = await res.json();
                            box.innerHTML = '';
                            if(data.headers) {
                                const hdrDiv = document.createElement('div');
                                hdrDiv.style.fontSize='12px'; hdrDiv.style.color='#333'; hdrDiv.style.marginBottom='6px';
                                hdrDiv.textContent = 'Detected headers: ' + JSON.stringify(data.headers);
                                box.appendChild(hdrDiv);
                            }
                            if(data.colMap) {
                                const mapDiv = document.createElement('div');
                                mapDiv.style.fontSize='12px'; mapDiv.style.color='#666'; mapDiv.style.marginBottom='6px';
                                mapDiv.textContent = 'Normalized map: ' + JSON.stringify(data.colMap);
                                box.appendChild(mapDiv);
                            }
                            if(data.rows && data.rows.length){
                                const table = document.createElement('table');
                                table.style.width = '100%';
                                table.style.borderCollapse = 'collapse';
                                data.rows.forEach(r=>{
                                    const tr = document.createElement('tr');
                                    tr.innerHTML = '<td style="padding:6px;border-bottom:1px solid #eee"><strong>'+ (r.title||'') +'</strong></td>' +
                                                   '<td style="padding:6px;border-bottom:1px solid #eee">rating: '+ (r.totalScore!==null? r.totalScore : '-') +'</td>' +
                                                   '<td style="padding:6px;border-bottom:1px solid #eee">reviews: '+ (r.reviewsCount!==null? r.reviewsCount : '-') +'</td>';
                                    table.appendChild(tr);
                                });
                                box.appendChild(table);
                                box.style.display = 'block';
                            } else {
                                box.textContent = 'Tidak ada baris data yang terbaca.';
                                box.style.display = 'block';
                            }
                        }catch(err){
                            box.textContent = 'Error: ' + err.message;
                            box.style.display = 'block';
                        } finally {
                            previewBtn.disabled = false;
                            previewBtn.textContent = 'Preview';
                        }
                    });
                }
            })();
        </script>
