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

                @if (session('status'))
                    <div class="alert alert-success mt-2 small" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->get('email'))
                    <div class="alert alert-danger mt-2" role="alert">
                        <h4 class="fw-bold fs-6">Gagal!</h4>
                        <ul class="px-3">
                            @foreach ((array) $errors->get('email') as $message)
                                <li class="small">{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="mt-3">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-success mb-0">Email</label>
                            <input 
                                type="email" 
                                name="email"
                                class="form-control" 
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>

                        <button type="submit" class="btn btn-success fw-semibold w-100">
                            Kirim Link Reset
                        </button>
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