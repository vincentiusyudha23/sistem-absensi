@extends('master')

@section('styles')
<style>
    .logo {
        width: 100px;
        height: auto;
    }

    /* Hilangkan glow bootstrap & ganti jadi border hijau */
    .form-control:focus {
        border-color: #198754; /* hijau bootstrap */
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
    <div class="card shadow-sm" style="width: 25rem;">
        <div class="card-body">
            
            <div class="text-center">
                <img src="/assets/img/logo-1.png" class="logo">
                <div class="text-success text-center">
                    <h3 class="mb-0 pb-1 border-bottom border-2 border-success d-inline-block fw-bold" style="font-size: large;">
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
                        <strong>Login gagal!</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('request.login') }}">
                    @csrf

                    {{-- Username --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-success mb-0">Username</label>
                        <input 
                            type="text" 
                            name="username"
                            class="form-control" 
                            value="{{ old('username') }}"
                            required
                        >
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-success mb-0">Password</label>
                        <div class="input-group">
                            <input 
                                type="password" 
                                name="password"
                                id="password"
                                class="form-control"
                                required
                            >
                            <span class="input-group-text" onclick="togglePassword()">
                                👁️
                            </span>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-3 form-check">
                        <input 
                            type="checkbox" 
                            class="form-check-input" 
                            id="remember"
                            name="remember"
                        >
                        <label class="form-check-label text-success" for="remember">
                            Ingat Saya
                        </label>
                    </div>

                    {{-- Button --}}
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success fw-semibold">
                            Login
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
    {{-- Script --}}
    <script>
        function togglePassword() {
            let input = document.getElementById("password");
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>
@endsection