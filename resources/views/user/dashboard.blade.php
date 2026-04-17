@extends('master')

@section('styles')
    <style>
        #preview {
            max-height: 300px;
            object-fit: cover;
        }
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
            <div class="col-md-4 col-sm-6 col-12">
                <div class="card border-0 shadow-sm text-white" style="background: #198754;">
                    <div class="card-body d-flex align-items-center">
                        
                        <div class="me-3">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                        </div>

                        <div>
                            <h4 class="mb-0 fw-bold" id="text-tepatWaktu">{{ $user->absensi()->where('status', 1)->count() }}</h4>
                            <small>Tepat Waktu</small>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <div class="card border-0 shadow-sm text-white" style="background: #fd7e14;">
                    <div class="card-body d-flex align-items-center">
                        
                        <div class="me-3">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                        </div>

                        <div>
                            <h4 class="mb-0 fw-bold" id="text-terlambat">{{ $user->absensi()->where('status', 2)->count() }}</h4>
                            <small>Terlambat</small>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <div class="card border-0 shadow-sm text-white" style="background: #dc3545;">
                    <div class="card-body d-flex align-items-center">
                        
                        <div class="me-3">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-x-circle fs-4"></i>
                            </div>
                        </div>

                        <div>
                            <h4 class="mb-0 fw-bold" id="text-tidakHadir">{{ $user->absensi()->where('status', 3)->count() }}</h4>
                            <small>Tidak Hadir</small>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h1 class="fw-bold fs-5 mb-1">Status Absensi</h1>

                <div class="row g-2">
                    <div class="col-lg-4 col-12">
                        @if (empty($absensi?->waktu_keluar))
                            {!! \App\Enums\StatusAbsenEnum::statusHariIni($absensi?->status) !!}
                        @endif

                        @if (!empty($absensi) && !empty($absensi?->waktu_keluar))
                            <div class="card bg-info mb-2 border-0">
                                <div class="card-body text-white">
                                    <h5 class="fw-bold mb-1">Status hari ini</h5>

                                    <hr class="m-0 p-0">

                                    <div class="d-flex flex-wrap align-items-center mt-3 gap-2">
                                        <span class="badge bg-light text-danger fs-5">
                                            <i class="bi bi-check2 fs-3 text-info"></i>
                                        </span>

                                        <h3 class="fw-bold text-white mb-0">Sudah Check-Out</h3>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card bg-secondary border-0 mb-2" style="--bs-bg-opacity: .2;">
                            <div class="card-body">
                                <h4 class="fw-bold m-0 text-center" id="timer"></h4>
                                
                                @if ($absensi?->waktu_masuk || $absensi?->waktu_keluar)
                                    <hr>
                                
                                    <ul class="m-0 pb-0">
                                        @if ($absensi?->waktu_masuk)
                                            <li>Waktu Check-in : {{ \Carbon\Carbon::parse($absensi->waktu_masuk)->format('H:i') }} WIB</li>
                                        @endif
                                        @if ($absensi?->waktu_keluar)
                                            <li>Waktu Check-out : {{ \Carbon\Carbon::parse($absensi->waktu_keluar)->format('H:i') }} WIB</li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <i class="bi bi-geo-alt-fill text-success"></i>
                            <h5 class="fs-6 text-wrap" id="alamat-text">
                                
                            </h5>
                        </div>

                        <button type="button" id="resetbtnLokasi" class="w-100 btn btn-success fw-semibold">
                            Perbarui Lokasi
                        </button>
                    </div>
                    <div class="col-lg-8 col-12">
                        <div class="rounded" id="map" style="height: 300px;"></div>
                    </div>
                    <input name="latitude" type="hidden" id="latitude">
                    <input name="longitude" type="hidden" id="longitude">
                    <input name="addresses" type="hidden" id="addresses">
                </div>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-6 col-12">
                <button type="button" id="checkinBtn" {{ !empty($absensi) ? 'disabled' : '' }} class="btn fs-2 fw-bold btn-success text-center w-100">
                    <i class="bi bi-check-circle-fill mx-1"></i> Absen Masuk
                </button>
            </div>
            <div class="col-md-6 col-12">
                <button type="button" id="checkoutBtn" {{ empty($absensi) ? 'disabled' : '' }} class="btn fs-2 fw-bold btn-danger text-center w-100">
                    <i class="bi bi-x-circle-fill mx-1"></i> Absen Pulang
                </button>
            </div>
        </div>

        <div class="modal fade" id="modal-checkin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body text-center">
                        <!-- Kamera -->
                        <video id="video" class="w-100 rounded" autoplay></video>

                        <!-- Canvas (hidden) -->
                        <canvas id="canvas" class="d-none"></canvas>

                        <!-- Preview hasil foto -->
                        <img id="preview" class="img-fluid rounded mt-2 d-none"/>

                        <!-- Hidden input -->
                        <input type="hidden" id="photo" name="photo">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>

                        <button type="button" class="btn btn-warning d-none" id="retakeBtn">
                            Ambil Ulang
                        </button>

                        <button type="button" class="btn btn-success" id="captureBtn">
                            Ambil Foto
                        </button>

                        <button type="button" class="btn btn-primary d-none" id="submitBtn">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <livewire:absensi-table userId="{{ $user->id }}"/>
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
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function updateTime() {
                const now = new Date();

                let hours = String(now.getHours()).padStart(2, '0');
                let minutes = String(now.getMinutes()).padStart(2, '0');
                let seconds = String(now.getSeconds()).padStart(2, '0');

                const timeString = `${hours}:${minutes}:${seconds} WIB`;

                document.getElementById('timer').innerText = timeString;
            }

            setInterval(updateTime, 1000);
            updateTime();

            const checkinBtn = document.getElementById('checkinBtn');
            const checkoutBtn = document.getElementById('checkoutBtn');

            checkinBtn.disabled = true;
            checkoutBtn.disabled = true;

            let isGpsEnabled = false;
            let map = null;
            let marker = null;
            let watchId = null;
            let circle = null;
            const [userLat, userLng] = @json([$user->latitude, $user->longitude]);
            const radius = @json(config('app.radius_absen'));

            function enableButtons() {
                @if(empty($absensi))
                    checkinBtn.disabled = false;
                @endif

                @if(!empty($absensi) && empty($absensi->waktu_keluar))
                    checkoutBtn.disabled = false;
                @endif
            }

            function disableButtons(msg = "GPS tidak aktif") {
                checkinBtn.disabled = true;
                checkoutBtn.disabled = true;
            }

            // INIT MAP SEKALI SAJA
            function initMap(lat, lng) {
                map = L.map('map').setView([lat, lng], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup('Lokasi Anda')
                    .openPopup();

                circle = L.circle([userLat, userLng], {
                    color: 'blue',
                    fillColor: '#30a5ff',
                    fillOpacity: 0.3,
                    radius:  radius// meter
                }).addTo(map);
            }

            // UPDATE POSISI
            function updateMap(lat, lng) {
                if (!map) {
                    initMap(lat, lng);
                } else {
                    map.setView([lat, lng]);
                    marker.setLatLng([lat, lng]);
                }

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            }

            async function getAddress(lat, lng) {
                try {
                    let url = '{{ route("user.getMyAddress") }}' + `?lat=${lat}&lng=${lng}`;
                    const response = await fetch(url);
                    const data = await response.json();

                    return data.address || "Alamat tidak ditemukan";
                } catch (error) {
                    return "Gagal mengambil alamat";
                }
            }

            function checkGeolocation() {
                if (!navigator.geolocation) {
                    disableButtons('Browser tidak mendukung GPS');
                    return;
                }

                // ❗ hentikan watcher lama
                if (watchId !== null) {
                    navigator.geolocation.clearWatch(watchId);
                }

                watchId = navigator.geolocation.watchPosition(
                    async function(position) {
                        let lat = position.coords.latitude;
                        let lng = position.coords.longitude;

                        updateMap(lat, lng);

                        const address = await getAddress(lat, lng);

                        document.getElementById('alamat-text').innerText = address;
                        document.getElementById('addresses').value = address;

                        if (!isGpsEnabled) {
                            enableButtons();
                            isGpsEnabled = true;
                        }
                    },
                    function(error) {
                        isGpsEnabled = false;

                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                disableButtons("Akses lokasi ditolak");
                                break;
                            case error.POSITION_UNAVAILABLE:
                                disableButtons("Lokasi tidak tersedia");
                                break;
                            case error.TIMEOUT:
                                disableButtons("Timeout mengambil lokasi");
                                break;
                            default:
                                disableButtons("Gagal mengambil lokasi");
                        }
                    },
                    {
                        enableHighAccuracy: true,
                        maximumAge: 0,
                        timeout: 5000
                    }
                );
            }

            checkGeolocation();

            const resetbtnLokasi = document.getElementById('resetbtnLokasi');

            resetbtnLokasi.addEventListener('click', function(e){
                e.preventDefault();

                checkGeolocation();
                resetbtnLokasi.innerText = "Mengambil lokasi...";
                resetbtnLokasi.disabled = true;

                setTimeout(() => {
                    resetbtnLokasi.innerText = "Perbarui Lokasi";
                    resetbtnLokasi.disabled = false;
                }, 2000);
            });

            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const preview = document.getElementById('preview');
            const captureBtn = document.getElementById('captureBtn');
            const submitBtn = document.getElementById('submitBtn');
            const photoInput = document.getElementById('photo');
            const retakeBtn = document.getElementById('retakeBtn');
            let stream = null;

            async function handleOpenCamera(){
                if (!isGpsEnabled) {
                    e.preventDefault();
                    alert("Aktifkan GPS terlebih dahulu!");
                    return;
                }
                
                const modalCheckIn = new bootstrap.Modal('#modal-checkin', {
                    keyboard: false
                });

                modalCheckIn.show();

                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "user"
                        },
                        audio: false
                    });

                    video.srcObject = stream;

                } catch (err) {
                    alert("Tidak bisa mengakses kamera");
                }
            };

            let textTitleModal = document.getElementById('exampleModalLabel');

            checkinBtn.addEventListener('click', async function(e) {
                textTitleModal.innerText = 'Absen Masuk';
                await handleOpenCamera();
            });

            checkoutBtn.addEventListener('click', async function (e) {
                textTitleModal.innerText = 'Absen Pulang';
                await handleOpenCamera();
            });

            captureBtn.addEventListener('click', function() {
                const ctx = canvas.getContext('2d');

                const MAX_WIDTH = 640;
                let width = video.videoWidth;
                let height = video.videoHeight;

                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }

                canvas.width = width;
                canvas.height = height;

                ctx.drawImage(video, 0, 0, width, height);

                const imageData = canvas.toDataURL('image/jpeg', 0.7);

                preview.src = imageData;
                preview.classList.remove('d-none');

                video.classList.add('d-none');

                photoInput.value = imageData;

                captureBtn.classList.add('d-none');
                submitBtn.classList.remove('d-none');
                retakeBtn.classList.remove('d-none');
            });

            submitBtn.addEventListener('click', async function() {
                await sendData();
            });

            async function sendData(){
                submitBtn.disabled = true;
                Swal.showLoading();

                try{
                    const response = await fetch('{{ route("user.store_absen") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            latitude: document.getElementById('latitude').value,
                            longitude: document.getElementById('longitude').value,
                            address: document.getElementById('addresses').value,
                            image: photoInput.value
                        })
                    });

                    const data = await response.json();
                    
                    if(data.success && response.ok){
                        Swal.hideLoading();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.msg,
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            location.reload(); // atau redirect
                        });
                    }else{
                        submitBtn.disabled = false;
                        Swal.fire('Gagal!', data.msg ?? 'Terjadi kesalahan.', 'error');
                    }
                } catch (error){
                    modalCheckIn.hide();
                    Swal.fire('Error!', 'Gagal terhubung ke server.', 'error');
                }
            }

            retakeBtn.addEventListener('click', function() {

                // reset preview
                preview.classList.add('d-none');
                video.classList.remove('d-none');

                photoInput.value = '';

                // tombol
                captureBtn.classList.remove('d-none');
                submitBtn.classList.add('d-none');
                retakeBtn.classList.add('d-none');
            });

            document.getElementById('modal-checkin').addEventListener('hidden.bs.modal', function () {

                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                // reset UI
                video.classList.remove('d-none');
                preview.classList.add('d-none');

                captureBtn.classList.remove('d-none');
                submitBtn.classList.add('d-none');
                retakeBtn.classList.add('d-none');

                photoInput.value = '';
            });
        })
        
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

            let url = "{{ route('user.detail_absensi', ['id' => '__ID__']) }}".replace('__ID__', id);

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