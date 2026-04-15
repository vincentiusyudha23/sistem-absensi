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
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-bg-primary">
                                <i class="bi bi-check2 fs-3"></i>
                            </span>
                            <h5 class="fw-semibold mb-0">Hadir</h5>
                        </div>
                        <div class="text-center text-success py-2">
                            <h1 class="d-inline-block mx-2" style="font-weight: 900;">{{ $user->absensi()?->where('status', 1)->count() }}</h1>
                            <span class="fs-3">Tepat Waktu</span>
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
                            <h1 class="d-inline-block mx-2" style="font-weight: 900;">{{ $user->absensi()?->where('status', 2)->count() }}</h1>
                            <span class="fs-3">Terlambat</span>
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
                            <h1 class="d-inline-block mx-2" style="font-weight: 900;">{{ $user->absensi()?->where('status', 3)->count() }}</h1>
                            <span class="fs-3">Tidak Hadir</span>
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
                    <i class="bi bi-check-circle-fill mx-1"></i> Check-In
                </button>
            </div>
            <div class="col-md-6 col-12">
                <button type="button" id="checkoutBtn" {{ empty($absensi) ? 'disabled' : '' }} class="btn fs-2 fw-bold btn-danger text-center w-100">
                    <i class="bi bi-x-circle-fill mx-1"></i> Check-Out
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

        <div class="card">
            <div class="card-body">
                <h1 class="fw-bold fs-5 mb-1">Riwayat Absensi</h1>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col" class="text-center">Waktu Check-In</th>
                                <th scope="col">Keterangan Check-In</th>
                                <th scope="col" class="text-center">Waktu Check-Out</th>
                                <th scope="col">Keterangan Check-Out</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->absensi()?->orderBy('created_at', 'desc')->limit(10)->get() as $absen)
                                <tr>
                                    <td>{{ $absen->created_at->translatedFormat('l, d-m-Y') }}</td>
                                    <td class="text-center">{{ Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i') }}</td>
                                    <td class="d-flex flex-column">
                                        @if ($absen->image_masuk)
                                            <a href="{{ asset('storage/' . $absen->image_masuk) }}" target="_blank" class="text-center">
                                                <img src="{{ asset('storage/' . $absen->image_masuk) }}" alt="foto" width="100" height="auto">
                                            </a>
                                        @endif
                                        {{ $absen->address1 }}
                                    </td>
                                    <td class="text-center">{{ Carbon\Carbon::parse($absen->waktu_keluar)->format('H:i') }}</td>
                                    <td class="d-flex flex-column">
                                        @if ($absen->image_keluar)
                                            <a href="{{ asset('storage/' . $absen->image_keluar) }}" target="_blank" class="text-center">
                                                <img src="{{ asset('storage/' . $absen->image_keluar) }}" alt="foto" width="100" height="auto">
                                            </a>
                                        @endif
                                        {{ $absen->address2 }}
                                    </td>
                                    <td>{!! \App\Enums\StatusAbsenEnum::getBadgeStatusAbsen($absen->status) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`
                    );

                    const data = await response.json();

                    return data.display_name; // alamat lengkap
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
                textTitleModal.innerText = 'Check-In';
                await handleOpenCamera();
            });

            checkoutBtn.addEventListener('click', async function (e) {
                textTitleModal.innerText = 'Check-Out';
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
    </script>
@endsection