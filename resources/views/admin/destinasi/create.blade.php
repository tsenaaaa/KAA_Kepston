@extends('layouts.app')

@section('content')
<div class="main-content">
    <div style="max-width:720px;margin:0 auto;padding:24px;background:white;border-radius:8px;">
        <h1 class="text-xl font-semibold mb-4">Tambah Destinasi</h1>

        @if($errors->any())
            <div class="mb-4 p-3 rounded bg-red-50 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.destinasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input name="nama" class="mt-1 block w-full rounded border-gray-200" value="{{ old('nama') }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" class="mt-1 block w-full rounded border-gray-200">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <div style="position:relative;">
                    <input id="alamat" name="alamat" class="mt-1 block w-full rounded border-gray-200" value="{{ old('alamat') }}" autocomplete="off" tabindex="0" style="position:relative;z-index:2000;">
                    <div id="alamat-suggestions" class="mt-1 bg-white border rounded shadow-sm" style="display:none;position:absolute;left:0;right:0;top:100%;max-height:200px;overflow:auto;z-index:2001;"></div>
                </div>
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                <div class="mt-2">
                    <button type="button" id="toggle-map" class="mt-2 bg-gray-200 px-3 py-1 rounded">Tampilkan Peta</button>
                    <div id="map" class="mt-2" style="height:300px;z-index:0;display:none;"></div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <input name="kategori" class="mt-1 block w-full rounded border-gray-200" value="{{ old('kategori') }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Foto (unggah)</label>
                <input type="file" name="foto" class="mt-1 block w-full">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">TikTok (URL)</label>
                <input name="tiktok" class="mt-1 block w-full rounded border-gray-200" value="{{ old('tiktok') }}">
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
                <button class="bg-red-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>
        <!-- Leaflet CSS/JS and Nominatim autocomplete script -->
        <link id="leaflet-css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
        

        <style>
            #alamat-suggestions div{padding:8px;cursor:pointer}
            #alamat-suggestions div:hover{background:#f3f4f6}
        </style>

        <script>
            const defaultLat = {{ old('latitude', -6.2) }};
            const defaultLng = {{ old('longitude', 106.816666) }};
            (function(){

                let map = null;
                let marker = null;
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                const alamatInput = document.getElementById('alamat');
                const suggestions = document.getElementById('alamat-suggestions');
                const toggleMapBtn = document.getElementById('toggle-map');

                    function initMap(){
                    if(map) return;
                        map = L.map('map').setView([parseFloat(defaultLat), parseFloat(defaultLng)], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    map.on('click', async function(e){
                        const {lat,lng} = e.latlng;
                        setMarker(lat, lng);
                        try{
                            const r = await fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+lat+'&lon='+lng);
                            const data = await r.json();
                            if(data && data.display_name){
                                alamatInput.value = data.display_name;
                            }
                        }catch(err){console.error(err)}
                    });

                    // if old values exist, show marker
                    if(latInput.value && lngInput.value){
                        setMarker(latInput.value, lngInput.value);
                    }
                }

                function setMarker(lat,lng){
                    lat = parseFloat(lat);
                    lng = parseFloat(lng);
                    if(!map){
                        // if map not initialized, show and init
                        document.getElementById('map').style.display = 'block';
                        initMap();
                    }
                    if(marker) marker.setLatLng([lat,lng]); else marker = L.marker([lat,lng]).addTo(map);
                    map.setView([lat,lng],15);
                    latInput.value = lat;
                    lngInput.value = lng;
                }

                async function geocodeAddress(q){
                    if(!q) return null;
                    try{
                        const url = 'https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=1&q='+encodeURIComponent(q);
                        const res = await fetch(url, {headers:{'Accept':'application/json'}});
                        const items = await res.json();
                        if(items && items.length){
                            return items[0];
                        }
                    }catch(err){ console.error('geocode error', err) }
                    return null;
                }

                // search (Nominatim)
                let timeout = null;
                alamatInput.addEventListener('input', function(e){
                    const q = e.target.value.trim();
                    if(!q){ suggestions.style.display='none'; return; }
                    clearTimeout(timeout);
                    timeout = setTimeout(async ()=>{
                        try{
                            const url = 'https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&limit=5&q='+encodeURIComponent(q);
                            const res = await fetch(url, {headers:{'Accept':'application/json'}});
                            const items = await res.json();
                            suggestions.innerHTML = '';
                            if(items && items.length){
                                items.forEach(it=>{
                                    const div = document.createElement('div');
                                    div.textContent = it.display_name;
                                    div.dataset.lat = it.lat;
                                    div.dataset.lon = it.lon;
                                    div.addEventListener('click', function(){
                                        alamatInput.value = this.textContent;
                                        suggestions.style.display = 'none';
                                                    setMarker(this.dataset.lat, this.dataset.lon);
                                                    lastGeocodedValue = this.textContent;
                                    });
                                    suggestions.appendChild(div);
                                });
                                suggestions.style.display = 'block';
                            } else {
                                suggestions.style.display = 'none';
                            }
                        }catch(err){
                            console.error(err);
                            suggestions.style.display = 'none';
                        }
                    }, 300);
                });

                // hide suggestions when clicking outside
                document.addEventListener('click', function(e){
                    if(!document.getElementById('alamat-suggestions').contains(e.target) && e.target !== alamatInput){
                        suggestions.style.display = 'none';
                    }
                });

                // immediate geocode on Enter or on blur (if coords not present)
                let lastGeocodedValue = '';
                alamatInput.addEventListener('keydown', async function(e){
                    if(e.key === 'Enter'){
                        e.preventDefault();
                        const q = alamatInput.value.trim();
                        if(!q) return;
                        const r = await geocodeAddress(q);
                        if(r){
                            alamatInput.value = r.display_name;
                            setMarker(r.lat, r.lon);
                            lastGeocodedValue = alamatInput.value;
                        }
                    }
                });

                alamatInput.addEventListener('blur', async function(){
                    const q = alamatInput.value.trim();
                    if(!q) return;
                    if(latInput.value && lngInput.value) return; // already have coords
                    if(q === lastGeocodedValue) return; // already geocoded
                    const r = await geocodeAddress(q);
                    if(r){
                        alamatInput.value = r.display_name;
                        setMarker(r.lat, r.lon);
                        lastGeocodedValue = alamatInput.value;
                    }
                });

                // toggle map
                toggleMapBtn.addEventListener('click', function(){
                    const mapEl = document.getElementById('map');
                    if(mapEl.style.display === 'none' || mapEl.style.display === ''){
                        mapEl.style.display = 'block';
                        ensureLeafletLoaded().then(()=>{
                            initMap();
                            setTimeout(()=>{ if(map) map.invalidateSize(); }, 300);
                        }).catch(err=>console.error('Leaflet load error', err));
                        toggleMapBtn.textContent = 'Sembunyikan Peta';
                    } else {
                        mapEl.style.display = 'none';
                        toggleMapBtn.textContent = 'Tampilkan Peta';
                    }
                });

                function ensureLeafletLoaded(){
                    return new Promise((resolve, reject)=>{
                        if(window.L) return resolve();
                        // load script dynamically
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js';
                        script.onload = ()=>{ console.log('Leaflet loaded'); resolve(); };
                        script.onerror = (e)=> reject(e);
                        document.head.appendChild(script);
                    });
                }
            })();
        </script>
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
                                const res = await fetch('{{ route("admin.destinasi.csvPreview") }}', {method:'POST', body: fd});
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
    @endsection
