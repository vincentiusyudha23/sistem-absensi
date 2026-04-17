@extends('master')

@section('content')
<section class="container">
    <div class="w-100 mb-3">
        <h2 class="fw-bold mb-1">Tambah Anggota</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.list_users') }}">Daftar Anggota</a>
                </li>
                <li class="breadcrumb-item active">Tambah Anggota</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.tambah_anggota.store') }}" method="POST">
                @csrf

                <div class="row">

                    <!-- Nama -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Nama</label>
                        <input 
                            type="text" 
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Masukkan nama"
                            value="{{ old('name') }}"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NRP -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">NRP / NIP</label>
                        <input 
                            type="number" 
                            name="nrp"
                            class="form-control @error('nrp') is-invalid @enderror"
                            placeholder="Masukkan NRP/NIP"
                            value="{{ old('nrp') }}"
                            required
                        >
                        @error('nrp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Jabatan</label>
                        <input 
                            type="text" 
                            name="jabatan"
                            class="form-control @error('jabatan') is-invalid @enderror"
                            placeholder="Contoh: staff"
                            value="{{ old('jabatan') }}"
                            required
                        >
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Divisi -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Divisi</label>
                        <input 
                            type="text" 
                            name="divisi"
                            class="form-control @error('divisi') is-invalid @enderror"
                            placeholder="Masukkan Divisi"
                            value="{{ old('divisi') }}"
                            required
                        >
                        @error('divisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <div class="rounded" id="map" style="height: 300px;"></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Latitude</label>

                        <input 
                            type="text" 
                            id="lat"
                            name="latitude"
                            class="form-control @error('latitude') is-invalid @enderror"
                            placeholder="Masukkan Latitude"
                            value="{{ old('latitude') }}"
                            required
                        >
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Longitude</label>

                        <input 
                            type="text" 
                            id="lng"
                            name="longitude"
                            class="form-control @error('longitude') is-invalid @enderror"
                            placeholder="Masukkan Longitude"
                            value="{{ old('longitude') }}"
                            required
                        > 
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <!-- Button -->
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('admin.list_users') }}" class="btn btn-secondary me-2">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        let map = null;
        let marker = null;
        let defaultLat = -6.208437147272047;
        let defaultLng = 106.85999880653159;

        function initMap(lat, lng) {
            // kalau map sudah ada → hapus dulu
            if (map !== null) {
                map.remove();
            }

            map = L.map('map').setView([lat, lng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            map.on('click', function(e) {
                const { lat, lng } = e.latlng;

                // kalau marker belum ada → buat
                if (!marker) {
                    marker = L.marker([lat, lng], {
                        draggable: true
                    }).addTo(map);

                    // event drag (dipasang sekali saja)
                    marker.on('dragend', function() {
                        const pos = marker.getLatLng();

                        document.getElementById('lat').value = pos.lat;
                        document.getElementById('lng').value = pos.lng;
                    });
                } else {
                    // kalau sudah ada → pindahkan
                    marker.setLatLng([lat, lng]);
                }

                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
            });
        }

        function updateFromInput() {
            const lat = parseFloat(document.getElementById('lat').value || defaultLat);
            const lng = parseFloat(document.getElementById('lng').value || defaultLng);

            if (!isNaN(lat) && !isNaN(lng)) {
                initMap(lat, lng);
            }
        }

        updateFromInput();

        document.getElementById('lat').addEventListener('change', updateFromInput);
        document.getElementById('lng').addEventListener('change', updateFromInput);
    </script>
@endsection