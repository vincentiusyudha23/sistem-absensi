@extends('master')

@section('styles')
    <style>
        .personel-row:last-child { border-bottom: none !important; }
        .personel-row:hover { background-color: #f8f9fa; }
    </style>
@endsection

@section('content')
    <section>
        <div class="w-100">
            <h2 class="fw-bold mb-1">Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>

        <div class="w-100 row g-1 mb-3">
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-bg-primary">
                                <i class="bi bi-check2 fs-3"></i>
                            </span>
                            <h5 class="fw-semibold mb-0">Hari ini</h5>
                        </div>
                        <div class="text-center text-success py-2">
                            <h1 class="d-inline-block mx-2" style="font-weight: 900;">{{ $absens->where('status', 1)->count() }}</h1>
                            <span class="fs-3">
                                Hadir
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-bg-warning text-white">
                                <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                            </span>
                            <h5 class="fw-semibold mb-0">Terlambat</h5>
                        </div>
                        <div class="text-center text-warning py-2">
                            <h1 class="d-inline-block mx-2" style="font-weight: 900;">{{ $absens->where('status', 2)->count() }}</h1>
                            <span class="fs-3">
                                Anggota
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-bg-danger">
                                <i class="bi bi-x fs-3"></i>
                            </span>
                            <h5 class="fw-semibold mb-0">Tidak Hadir</h5>
                        </div>
                        <div class="text-center text-danger py-2">
                            <h1 class="d-inline-block mx-2" style="font-weight: 900;">{{ $absens->where('status', 3)->count() }}</h1>
                            <span class="fs-3">
                                Anggota
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="fw-bold">Data Absensi Hari Ini</h5>

                <livewire:dashboard-absensi-table/>
            </div>
        </div>

        <div class="row g-1">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold">Monitoring Kehadiran</h5>
                        
                        <div class="list-absens" id="list-absens">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold">Lokasi Anggota</h5>
                        <div class="rounded" id="map" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let map = null;
            let markers = {};

            const defaultLat = -6.200000;
            const defaultLng = 106.816666;

            function initMap(lat = defaultLat, lng = defaultLng) {
                map = L.map('map').setView([lat, lng], 10);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
            }

            async function fetchLocations() {
                try {
                    let response = await fetch('{{ route("admin.fetch_data_absensi") }}'); // 🔥 ganti URL kamu
                    let data = await response.json();

                    updateMarkers(data);
                    renderList(data);
                } catch (error) {
                    console.error('Gagal ambil lokasi:', error);
                }
            }

            function updateMarkers(locations) {
                if (!map) {
                    if (locations.length > 0) {
                        initMap(locations[0].lat, locations[0].lng);
                    } else {
                        initMap(); // pakai default
                    }
                }

                locations.forEach(loc => {
                    let key = loc.id; // pastikan ada ID unik

                    if (markers[key]) {
                        // ✅ update posisi marker
                        markers[key].setLatLng([loc.lat, loc.lng]);
                    } else {
                        // ✅ buat marker baru
                        let marker = L.marker([loc.lat, loc.lng]).addTo(map)
                            .bindPopup(`<b>${loc.name}</b>`)
                            .bindTooltip(loc.name, { permanent: true, direction: 'top' });

                        markers[key] = marker;
                    }
                });
            }

            function renderList(locations) {
                let container = document.getElementById('list-absens');

                // kosongkan dulu biar tidak duplicate
                container.innerHTML = '';

                if (locations.length === 0) {
                    container.innerHTML = `<div class="text-center py-3 text-muted">Belum ada data absensi</div>`;
                    return;
                }

                locations.forEach(loc => {
                    let firstLetter = loc.name.charAt(0).toUpperCase();

                    let html = `
                        <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom personel-row">
                            <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center fw-semibold text-white"
                                style="width:46px; height:46px; background-color: #3B6D11; font-size:13px;">
                                ${firstLetter}
                            </div>

                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-truncate" style="font-size: 14px;">
                                    ${loc.name}
                                    <span class="fw-normal text-muted">- ${loc.divisi ?? '-'}</span>
                                </div>
                                <div style="font-size: 13px; color: #555;">
                                    ${loc.waktu_masuk ?? '-'}
                                </div>
                            </div>

                            <div class="text-end flex-shrink-0" style="min-width: 80px;">
                                <div class="fw-semibold" style="font-size: 13px;">
                                    ${loc.waktu_masuk ?? '-'}
                                </div>

                                <span class="badge rounded-pill ${loc.status == 1 ? 'bg-success' : 'bg-warning'}" style="font-size: 10px;">
                                    ${loc.status == 1 ? 'Tepat Waktu' : 'Terlambat'}
                                </span>
                            </div>
                        </div>
                    `;

                    container.insertAdjacentHTML('beforeend', html);
                });
            }

            initMap();

            // 🔥 pertama kali load
            fetchLocations();

            // 🔁 ulang tiap 5 detik
            setInterval(fetchLocations, 5000);

        });
    </script>
@endsection