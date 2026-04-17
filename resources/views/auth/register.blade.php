@extends('master')

@section('styles')
    <style>
        .logo {
            width: 100px;
            height: auto;
        }

        /* Hilangkan glow bootstrap & ganti jadi border hijau */
        .form-control:focus {
            border-color: #198754;
            /* hijau bootstrap */
            box-shadow: none !important;
        }

        .input-group-text {
            cursor: pointer;
            background-color: #fff;
        }

        @media (min-width: 768px) {
            .h-md-100 {
                height: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <section class="w-100 bg-success d-flex justify-content-center align-items-center px-2">
        <div class="card my-4 shadow-sm" style="width: 35rem;">
            <div class="card-body">
                <div class="text-center">
                    <img src="/assets/img/logo-1.png" class="logo">
                    <div class="text-success text-center">
                        <h3 class="mb-0 pb-1 border-bottom border-2 border-success d-inline-block fw-bold"
                            style="font-size: large;">
                            SISTEM ABSENSI PUSPALAD
                        </h3>
                        <span class="d-block mt-0" style="font-size: small;">
                            PUSAT PERALATAN ANGKATAN DARAT
                        </span>
                    </div>
                </div>

                <div class="mt-3">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Terjadi kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" class="px-md-1">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required fw-semibold text-success mb-0">Nama</label>
                                <div class="input-group">
                                    <input class="form-control" required type="text" name="name"
                                        value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required fw-semibold text-success mb-0">NRP / NIP</label>
                                <div class="input-group">
                                    <input class="form-control" required type="text" name="nrp"
                                        value="{{ old('nrp') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required fw-semibold text-success mb-0">Jabatan</label>
                                <div class="input-group">
                                    <input class="form-control" required type="text" name="jabatan"
                                        value="{{ old('jabatan') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required fw-semibold text-success mb-0">Divisi</label>
                                <div class="input-group">
                                    <input class="form-control" required type="text" name="divisi"
                                        value="{{ old('divisi') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required fw-semibold text-success mb-0">Username</label>
                                <div class="input-group">
                                    <input class="form-control" required type="text" name="username"
                                        value="{{ old('username') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label required fw-semibold text-success mb-0">Email</label>
                                <div class="input-group">
                                    <input class="form-control" required type="email" name="email"
                                        value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-success required mb-0">Password</label>
                                <div class="input-group">
                                    <input class="form-control" required type="password" name="password">
                                    <span class="input-group-text" style="cursor: pointer;" id="eye_new_pw">
                                        <i class="bi bi-eye-fill"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-success required mb-0">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input class="form-control" required type="password" name="password_confirmation">
                                    <span class="input-group-text" style="cursor: pointer;" id="eye_new_pw_corn">
                                        <i class="bi bi-eye-fill"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold text-success required mb-1">Lokasi Rumah</label>
                                <div class="rounded" id="map-regis" style="height: 300px;"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-success required mb-0">Latitude</label>
                                <div class="input-group">
                                    <input class="form-control" id="lat" required type="" name="latitude"
                                        value="{{ old('latitude') }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-success required mb-0">longitude</label>
                                <div class="input-group">
                                    <input class="form-control" id="lng" required type="text" name="longitude"
                                        value="{{ old('longitude') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-success w-100 fw-semibold">
                                Buat Akun
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-2">
                        <a class="text-success" href="{{ route('login') }}">Sudah punya akun?</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function togglePassword(inputName, eyeId) {
            const input = document.querySelector(`input[name="${inputName}"]`);
            const eye = document.querySelector(`#${eyeId} i`);

            if (input.type === "password") {
                input.type = "text";
                eye.classList.remove("bi-eye-fill");
                eye.classList.add("bi-eye-slash-fill");
            } else {
                input.type = "password";
                eye.classList.remove("bi-eye-slash-fill");
                eye.classList.add("bi-eye-fill");
            }
        }

        // Event listener
        document.getElementById('eye_new_pw').addEventListener('click', function() {
            togglePassword('password', 'eye_new_pw');
        });

        document.getElementById('eye_new_pw_corn').addEventListener('click', function() {
            togglePassword('password_confirmation', 'eye_new_pw_corn');
        });

        let map = null;
        let marker = null;

        function initMap(lat, lng) {
            // kalau map sudah ada → hapus dulu
            if (map !== null) {
                map.remove();
            }

            map = L.map('map-regis').setView([lat, lng], 15);

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

        document.getElementById('lat').addEventListener('change', updateFromInput);
        document.getElementById('lng').addEventListener('change', updateFromInput);

        // ambil lokasi user
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    initMap(lat, lng);
                },
                function(error) {
                    console.log("Gagal ambil lokasi:", error);

                    // fallback kalau user nolak izin
                    initMap(-6.200000, 106.816666); // Jakarta
                }
            );
        } else {
            alert("Browser tidak mendukung geolocation");
            initMap(-6.200000, 106.816666);
        }
    </script>
@endsection
