@extends('master')

@section('content')
<section class="container">
    <div class="w-100 mb-3">
        <h2 class="fw-bold mb-1">Edit Anggota</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.list_users') }}">Daftar Anggota</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.detail_anggota', $user->id) }}">Detail Anggota</a>
                </li>
                <li class="breadcrumb-item active">Edit Anggota</li>
            </ol>
        </nav>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.edit_anggota.update', ['id' => $user->id]) }}" method="POST">
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
                            value="{{ $user->name }}"
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
                            type="text" 
                            name="nrp"
                            class="form-control @error('nrp') is-invalid @enderror"
                            placeholder="Masukkan NRP/NIP"
                            value="{{ $user->nrp }}"
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
                            value="{{ $user->jabatan }}"
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
                            value="{{ $user->divisi }}"
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
                            value="{{ $user->latitude ?? -6.208437147272047 }}"
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
                            value="{{ $user->longitude ?? 106.85999880653159 }}"
                            required
                        > 
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" {{ $user->status ? 'checked' : '' }} role="switch" id="switchCheckDefault">
                    <label class="form-check-label" for="switchCheckDefault">Status</label>
                </div>

                <!-- Button -->
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('admin.detail_anggota', $user->id) }}" class="btn btn-secondary me-2">
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

        function initMap(lat, lng) {
            // kalau map sudah ada → hapus dulu
            if (map !== null) {
                map.remove();
            }

            map = L.map('map').setView([lat, lng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);

            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;

            map.on('click', function(e) {
                const { lat, lng } = e.latlng;
                marker.setLatLng([lat, lng]);

                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
            });

            marker.on('dragend', function() {
                const pos = marker.getLatLng();

                document.getElementById('lat').value = pos.lat;
                document.getElementById('lng').value = pos.lng;
            });
        }

        function updateFromInput() {
            const lat = parseFloat(document.getElementById('lat').value);
            const lng = parseFloat(document.getElementById('lng').value);

            if (!isNaN(lat) && !isNaN(lng)) {
                initMap(lat, lng);
            }
        }

        updateFromInput();

        document.getElementById('lat').addEventListener('change', updateFromInput);
        document.getElementById('lng').addEventListener('change', updateFromInput);
    </script>
@endsection