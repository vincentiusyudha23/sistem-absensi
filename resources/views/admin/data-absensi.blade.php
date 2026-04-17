@extends('master')

@section('content')
    <section>
        <div class="w-100">
            <h2 class="fw-bold mb-1">Data Absensi</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Data Absensi</li>
                </ol>
            </nav>
        </div>

        <div class="w-100 row g-1 mb-3">
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card card-body">
                    <div class="w-100 d-flex align-items-center gap-2">
                        <span class="text-success fs-4">
                            <i class="bi bi-person-fill-add"></i>
                        </span>

                        <h5 class="fw-semibold mb-0">Hadir</h5>
                    </div>

                    <h1 class="mx-3" id="text-tepatWaktu" style="font-weight: 900;">
                        {{ $absens->where('status', 1)->count() }}
                    </h1>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <div class="card card-body">
                    <div class="w-100 d-flex align-items-center gap-2">
                        <span class="text-secondary fs-4">
                            <i class="bi bi-clock-fill"></i>
                        </span>

                        <h5 class="fw-semibold mb-0">Telat</h5>
                    </div>

                    <h1 class="mx-3" id="text-terlambat" style="font-weight: 900;">
                        {{ $absens->where('status', 2)->count() }}
                    </h1>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <div class="card card-body">
                    <div class="w-100 d-flex align-items-center gap-2">
                        <span class="text-danger fs-4">
                            <i class="bi bi-x-circle-fill"></i>
                        </span>

                        <h5 class="fw-semibold mb-0">Tidak Hadir</h5>
                    </div>

                    <h1 class="mx-3" id="text-tidakHadir" style="font-weight: 900;">
                        {{ $absens->where('status', 3)->count() }}
                    </h1>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-1">
                    <div class="col-md-4 col-12">
                        <label class="form-label mb-1 fw-semibold">Filter Tanggal</label>
                        <div class="d-flex align-items-center gap-1">
                            <div class="input-group">
                                <input class="form-control" id="filter-tanggal-min" type="date">
                            </div>
                            <span>To</span>
                            <div class="input-group">
                                <input class="form-control" id="filter-tanggal-max" type="date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="form-label mb-1 fw-semibold">Filter Status</label>
                        <div class="input-group">
                            <select class="form-select" id="filter-status">
                                <option value="">-- Pilih status --</option>
                                <option value="1">Tepat Waktu</option>
                                <option value="2">Terlambat</option>
                                <option value="3">Tidak Hadir</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <label class="form-label mb-1 fw-semibold">Cari Nama / NRP...</label>
                        <div class="input-group">
                            <input class="form-control" id="filter-search" type="text" placeholder="Cari nama / NRP...">
                        </div>
                    </div>
                    <div class="col-md-1 col-12 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-filter btn-sm w-100 py-2 fw-semibold">Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="w-100 d-flex justify-content-end mb-2">
                    <button class="btn btn-light border fw-semibold" type="button" id="btn-export">Export</button>
                </div>
                <livewire:absensi-table />
            </div>
        </div>

        <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content position-relative">

                    {{-- Loading overlay --}}
                    <div class="modal-loading" id="modalLoading">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-clipboard2-check me-2"></i>Detail Absensi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- Personel Strip --}}
                        <div class="personel-strip">
                            <div class="personel-avatar" id="detail-avatar">
                                <span id="detail-initials"></span>
                            </div>
                            <div>
                                <div class="personel-name" id="detail-nama">-</div>
                                <div class="personel-meta" id="detail-meta">-</div>
                            </div>
                            <span class="ms-auto" id="detail-badge"></span>
                        </div>

                        {{-- Foto & Waktu --}}
                        <div class="section-label"><i class="bi bi-camera me-1"></i>Foto & Waktu Kehadiran</div>
                        <div class="row g-3 mb-3">

                            {{-- Absen Masuk Card --}}
                            <div class="col-6">
                                <div class="absen-card">
                                    <div class="absen-card-header checkin">
                                        <div class="dot-ci"></div>
                                        <span class="label-ci">Absen Masuk</span>
                                        <span class="time-val" id="detail-waktu-in">-</span>
                                    </div>
                                    <div class="absen-card-body">
                                        <div class="foto-box" id="foto-in-box" onclick="previewFoto('in')">
                                            <img id="foto-in" src="" alt="Foto Absen Masuk" style="display:none">
                                            <div class="no-foto" id="no-foto-in">
                                                <i class="bi bi-image"></i>
                                                <span>Tidak ada foto</span>
                                            </div>
                                            <span class="zoom-hint" id="zoom-in" style="display:none">
                                                <i class="bi bi-zoom-in"></i> Lihat
                                            </span>
                                        </div>
                                        <div class="addr-label">
                                            <i class="bi bi-geo-alt-fill" style="color:#2d7a3a"></i>
                                            Lokasi Absen Masuk
                                        </div>
                                        <div class="addr-text" id="detail-addr-in">-</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Absen Pulang Card --}}
                            <div class="col-6">
                                <div class="absen-card">
                                    <div class="absen-card-header checkout">
                                        <div class="dot-co"></div>
                                        <span class="label-co">Absen Pulang</span>
                                        <span class="time-val" id="detail-waktu-out">-</span>
                                    </div>
                                    <div class="absen-card-body">
                                        <div class="foto-box" id="foto-out-box" onclick="previewFoto('out')">
                                            <img id="foto-out" src="" alt="Foto Absen Pulang"
                                                style="display:none">
                                            <div class="no-foto" id="no-foto-out">
                                                <i class="bi bi-image"></i>
                                                <span>Tidak ada foto</span>
                                            </div>
                                            <span class="zoom-hint" id="zoom-out" style="display:none">
                                                <i class="bi bi-zoom-in"></i> Lihat
                                            </span>
                                        </div>
                                        <div class="addr-label">
                                            <i class="bi bi-geo-alt-fill" style="color:#e53935"></i>
                                            Lokasi Absen Pulang
                                        </div>
                                        <div class="addr-text" id="detail-addr-out">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Map Section --}}
                        <div class="section-label"><i class="bi bi-map me-1"></i>Titik Lokasi di Peta</div>
                        <div class="map-card">
                            <div class="map-card-header">
                                <i class="bi bi-pin-map-fill" style="color:#2d7a3a"></i>
                                <span>Peta Lokasi Absensi</span>
                                <a href="#" id="link-gmaps" target="_blank">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Buka Google Maps
                                </a>
                            </div>
                            <div id="detail-map"></div>
                            <div class="coords-strip">
                                <div class="coord-item">
                                    <div class="coord-label">📍 Koordinat Absen Masuk</div>
                                    <div class="coord-val" id="coord-in">-</div>
                                </div>
                                <div class="coord-item">
                                    <div class="coord-label">📍 Koordinat Absen Pulang</div>
                                    <div class="coord-val" id="coord-out">-</div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /modal-body --}}

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-2 px-3">
                        <h6 class="modal-title mb-0 fw-semibold" id="modalFotoLabel">Foto</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <img id="foto-preview-img" src="" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.querySelector('.btn-filter').addEventListener('click', function () {

            let tanggal_min = document.getElementById('filter-tanggal-min').value || null;
            let tanggal_max = document.getElementById('filter-tanggal-max').value || null;
            let status = document.getElementById('filter-status').value || null;
            let search = document.getElementById('filter-search').value || null;

            Livewire.dispatch('setFilterAbsensi', {
                tanggal_min: tanggal_min,
                tanggal_max: tanggal_max,
                status: status,
                search: search
            });

        });

        document.getElementById('btn-export').addEventListener('click', function() {
            Livewire.dispatch('exportData');
        });

        let detailMap = null;
        let markerIn = null;
        let markerOut = null;
        let fotoInUrl = '';
        let fotoOutUrl = '';

        /* ─── Custom pin icons ─────────────────────────────── */
        const iconIn = L.divIcon({
            className: '',
            html: `<div style="
        width:22px;height:22px;background:#2d7a3a;border-radius:50% 50% 50% 0;
        transform:rotate(-45deg);border:2px solid #fff;
        box-shadow:0 2px 5px rgba(0,0,0,.3)"></div>`,
            iconSize: [22, 22],
            iconAnchor: [11, 22],
            popupAnchor: [0, -26]
        });

        const iconOut = L.divIcon({
            className: '',
            html: `<div style="
        width:22px;height:22px;background:#e53935;border-radius:50% 50% 50% 0;
        transform:rotate(-45deg);border:2px solid #fff;
        box-shadow:0 2px 5px rgba(0,0,0,.3)"></div>`,
            iconSize: [22, 22],
            iconAnchor: [11, 22],
            popupAnchor: [0, -26]
        });

        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', async function (e) {
                await showDetail(this.dataset.id);
                if (e.target.classList.contains('btn-detail')) {
                    let id = e.target.dataset.id;
                    console.log('ID:', id);
                }
            });
        });


        /* ─── Buka modal detail ─────────────────────────────── */
        async function showDetail(id) {
            const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
            modal.show();

            // Reset & tampilkan loading
            document.getElementById('modalLoading').style.display = 'flex';
            resetModal();

            let url = "{{ route('admin.detail_absensi', ['id' => '__ID__']) }}".replace('__ID__', id);

            try {
                const res = await fetch(url, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) throw new Error('Gagal mengambil data');

                const data = await res.json();
                populateModal(data);

            } catch (err) {
                console.error(err);
                Swal.fire('Gagal!', 'Tidak dapat memuat detail absensi.', 'error');
                modal.hide();
            } finally {
                document.getElementById('modalLoading').style.display = 'none';
            }
        }


        /* ─── Isi data ke modal ─────────────────────────────── */
        function populateModal(d) {

            /* Personel info */
            const initials = (d.nama || '').split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
            document.getElementById('detail-initials').textContent = initials;
            document.getElementById('detail-nama').textContent = d.nama ?? '-';
            document.getElementById('detail-meta').textContent =
                `NRP: ${d.nrp ?? '-'}  ·  ${d.divisi ?? '-'}  ·  ${d.tanggal ?? '-'}`;

            const badgeMap = {
                hadir: ['badge text-bg-success', 'Tepat Waktu'],
                terlambat: ['badge text-bg-warning', 'Terlambat'],
                tidak: ['badge text-bg-danger', 'Tidak Hadir'],
                izin: ['badge-izin', 'Izin'],
            };
            const [cls, label] = badgeMap[d.status] ?? ['badge-tidak', d.status];
            document.getElementById('detail-badge').innerHTML =
                `<span class="${cls}">${label}</span>`;

            /* Waktu */
            document.getElementById('detail-waktu-in').textContent = d.check_in ? d.check_in + ' WIB' : '-';
            document.getElementById('detail-waktu-out').textContent = d.check_out ? d.check_out + ' WIB' : '-';

            /* Alamat */
            document.getElementById('detail-addr-in').textContent = d.alamat_in ?? 'Tidak tersedia';
            document.getElementById('detail-addr-out').textContent = d.alamat_out ?? 'Tidak tersedia';

            /* Foto Absen Masuk */
            fotoInUrl = d.foto_in ? `/storage/${d.foto_in}` : '';
            fotoOutUrl = d.foto_out ? `/storage/${d.foto_out}` : '';
            setFoto('in', fotoInUrl);
            setFoto('out', fotoOutUrl);

            /* Koordinat */
            const latIn = parseFloat(d.lat_in ?? 0);
            const lngIn = parseFloat(d.lng_in ?? 0);
            const latOut = parseFloat(d.lat_out ?? 0);
            const lngOut = parseFloat(d.lng_out ?? 0);

            document.getElementById('coord-in').textContent = d.lat_in ? `${latIn.toFixed(5)}°, ${lngIn.toFixed(5)}°` : '-';
            document.getElementById('coord-out').textContent = d.lat_out ? `${latOut.toFixed(5)}°, ${lngOut.toFixed(5)}°` :
                '-';

            /* Google Maps link */
            const gmapsBase = d.lat_in && d.lat_out ?
                `https://www.google.com/maps/dir/${latIn},${lngIn}/${latOut},${lngOut}` :
                d.lat_in ?
                `https://www.google.com/maps?q=${latIn},${lngIn}` :
                '#';
            document.getElementById('link-gmaps').href = gmapsBase;

            /* Init / refresh peta */
            initMap(latIn, lngIn, latOut, lngOut, d);
        }


        /* ─── Set foto ──────────────────────────────────────── */
        function setFoto(type, url) {
            const img = document.getElementById(`foto-${type}`);
            const none = document.getElementById(`no-foto-${type}`);
            const zoom = document.getElementById(`zoom-${type}`);

            if (url) {
                img.src = url;
                img.style.display = 'block';
                none.style.display = 'none';
                zoom.style.display = 'block';
            } else {
                img.style.display = 'none';
                none.style.display = 'flex';
                zoom.style.display = 'none';
            }
        }


        /* ─── Preview foto fullscreen ───────────────────────── */
        function previewFoto(type) {
            const url = type === 'in' ? fotoInUrl : fotoOutUrl;
            if (!url) return;

            document.getElementById('modalFotoLabel').textContent =
                type === 'in' ? 'Foto Absen Masuk' : 'Foto Absen Pulang';
            document.getElementById('foto-preview-img').src = url;
            new bootstrap.Modal(document.getElementById('modalFoto')).show();
        }


        /* ─── Init Leaflet map ──────────────────────────────── */
        function initMap(latIn, lngIn, latOut, lngOut, d) {
            /* Destroy existing */
            if (detailMap) {
                detailMap.remove();
                detailMap = null;
            }

            const hasIn = d.lat_in && d.lng_in;
            const hasOut = d.lat_out && d.lng_out;

            if (!hasIn && !hasOut) {
                document.getElementById('detail-map').innerHTML =
                    `<div class="d-flex align-items-center justify-content-center h-100 text-muted" style="height:200px">
                <span><i class="bi bi-geo-slash me-2"></i>Data koordinat tidak tersedia</span>
            </div>`;
                return;
            }

            /* Center */
            const centerLat = hasIn ? latIn : latOut;
            const centerLng = hasIn ? lngIn : lngOut;

            detailMap = L.map('detail-map', {
                zoomControl: true
            }).setView([centerLat, centerLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 19,
            }).addTo(detailMap);

            const bounds = [];

            if (hasIn) {
                markerIn = L.marker([latIn, lngIn], {
                        icon: iconIn
                    })
                    .addTo(detailMap)
                    .bindPopup(
                        `<strong style="color:#2d7a3a">Absen Masuk</strong><br>${d.check_in ?? ''} WIB<br><small>${d.alamat_in ?? ''}</small>`
                        );
                bounds.push([latIn, lngIn]);
            }

            if (hasOut) {
                markerOut = L.marker([latOut, lngOut], {
                        icon: iconOut
                    })
                    .addTo(detailMap)
                    .bindPopup(
                        `<strong style="color:#e53935">Absen Pulang</strong><br>${d.check_out ?? ''} WIB<br><small>${d.alamat_out ?? ''}</small>`
                        );
                bounds.push([latOut, lngOut]);
            }

            /* Fit to both pins if both exist */
            if (bounds.length === 2) {
                detailMap.fitBounds(bounds, {
                    padding: [30, 30]
                });

                /* Garis antara dua titik */
                L.polyline(bounds, {
                    color: '#2d7a3a',
                    weight: 2,
                    dashArray: '6 5',
                    opacity: .7
                }).addTo(detailMap);
            }

            /* Fix leaflet tile after modal animation */
            setTimeout(() => detailMap.invalidateSize(), 400);
        }


        /* ─── Reset modal ke state awal ─────────────────────── */
        function resetModal() {
            ['detail-nama', 'detail-meta', 'detail-waktu-in', 'detail-waktu-out',
                'detail-addr-in', 'detail-addr-out', 'coord-in', 'coord-out'
            ]
            .forEach(id => document.getElementById(id).textContent = '-');

            document.getElementById('detail-badge').innerHTML = '';
            document.getElementById('detail-initials').textContent = '';
            setFoto('in', '');
            setFoto('out', '');

            if (detailMap) {
                detailMap.remove();
                detailMap = null;
            }
        }

        /* Destroy map when modal closes (memory leak prevention) */
        document.getElementById('modalDetail').addEventListener('hidden.bs.modal', () => {
            if (detailMap) {
                detailMap.remove();
                detailMap = null;
            }
        });
    </script>
@endsection
