@extends('master')

@section('content')
    <section>
        <div class="w-100">
            <h2 class="fw-bold mb-1">Profil</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profil</li>
                </ol>
            </nav>
        </div>

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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required fw-semibold mb-0">Nama</label>
                            <div class="input-group">
                                <input class="form-control" required type="text" name="name" value="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required fw-semibold mb-0">NRP / NIP</label>
                            <div class="input-group">
                                <input class="form-control" required type="text" readonly name="nrp" value="{{ $user->nrp }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required fw-semibold mb-0">Jabatan</label>
                            <div class="input-group">
                                <input class="form-control" required type="text" name="jabatan" value="{{ $user->jabatan }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required fw-semibold mb-0">Divisi</label>
                            <div class="input-group">
                                <input class="form-control" required type="text" name="divisi" value="{{ $user->divisi }}">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required fw-semibold mb-0">Username</label>
                            <div class="input-group">
                                <input class="form-control" required type="text" name="username" disabled value="{{ $user->username }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required fw-semibold mb-0">Email</label>
                            <div class="input-group">
                                <input class="form-control" required type="email" name="email" value="{{ $user->email }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold mb-0">Password Baru</label>
                            <div class="input-group">
                                <input class="form-control" type="password" name="password">
                                <span class="input-group-text" style="cursor: pointer;" id="eye_new_pw">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold mb-0">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input class="form-control" type="password" name="password_confirmation">
                                <span class="input-group-text" style="cursor: pointer;" id="eye_new_pw_corn">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
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
        document.getElementById('eye_new_pw').addEventListener('click', function () {
            togglePassword('password', 'eye_new_pw');
        });

        document.getElementById('eye_new_pw_corn').addEventListener('click', function () {
            togglePassword('password_confirmation', 'eye_new_pw_corn');
        });
    </script>
@endsection

