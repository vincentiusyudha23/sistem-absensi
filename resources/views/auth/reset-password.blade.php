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
    </style>
@endsection

@section('content')
    <section class="w-100 h-100 bg-success d-flex justify-content-center align-items-center px-2">
        <div class="card border-0 shadow-sm" style="width: 25rem;">
            <div class="card-body">
                <div class="text-center d-flex flex-column justify-content-center align-items-center">
                    <img src="/assets/img/logo-1.png" class="logo">
                    <div class="text-success text-center d-inline-block">
                        <h3 class="mb-0 pb-1 border-bottom border-2 border-success fw-bold" style="font-size: large;">
                            ABSENSI PUSPALAD
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

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-success mb-0">Email</label>
                            <div class="input-group">
                                <input type="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-success required mb-0">Password</label>
                            <div class="input-group">
                                <input class="form-control" type="password" name="password" required autocomplete="new-password">
                                <span class="input-group-text" style="cursor: pointer;" id="eye_new_pw">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-success required mb-0">Konfirmasi Password</label>
                            <div class="input-group">
                                <input class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                                <span class="input-group-text" style="cursor: pointer;" id="eye_new_pw_corn">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Reset Password</button>
                    </form>
                </div>
            </div>

            <footer class="footer-custom text-center">
                <div>© {{ \Carbon\Carbon::now()->format('Y') }} PUSPALAD - TNI AD</div>
                <div>All rights reserved.</div>
            </footer>
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
    </script>
@endsection