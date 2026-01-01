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
        #map {
            height: 250px !important;
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
        #map {
            height: 200px !important;
        }
        .text-gray-500 {
            font-size: 0.75rem;
        }
    }
</style>
@endsection

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
                <select name="kategori" class="mt-1 block w-full rounded border-gray-200" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Culinary" {{ old('kategori') === 'Culinary' ? 'selected' : '' }}>Culinary (Resto, Cafe, dll)</option>
                    <option value="Tourism" {{ old('kategori') === 'Tourism' ? 'selected' : '' }}>Tourism (Museum, Tempat Ibadah, Public Space, Hotel, dll)</option>
                    <option value="Shopping" {{ old('kategori') === 'Shopping' ? 'selected' : '' }}>Shopping (Pasar, Mall, Swalayan, dll)</option>
                </select>
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
                <label class="block text-sm font-medium text-gray-700">Rating & Reviews Count (Upload CSV)</label>
                <input type="file" name="csv_file" class="mt-1 block w-full" accept=".csv">
                <small class="text-gray-500">Upload CSV file with columns: title, totalScore, url, reviewsCount to auto-fill rating and reviews count based on name match.</small>
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
            (function(){
                const defaultLat = <?php echo json_encode(old('latitude', -6.2)); ?>;
                const defaultLng = <?php echo json_encode(old('longitude', 106.816666)); ?>;

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
    @endsection
