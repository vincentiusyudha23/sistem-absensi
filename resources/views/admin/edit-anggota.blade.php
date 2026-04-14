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
                        <select 
                            required
                            name="divisi"
                            class="form-select @error('divisi') is-invalid @enderror"
                        >
                            <option value="">-- Pilih Divisi --</option>
                            <option value="IT" {{ old('divisi') == 'IT' ? 'selected' : '' }}>IT</option>
                            <option value="Operasional" {{ old('divisi') == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="Logistik" {{ old('divisi') == 'Logistik' ? 'selected' : '' }}>Logistik</option>
                        </select>
                        @error('divisi')
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
                    <a href="{{ route('admin.list_users') }}" class="btn btn-secondary me-2">
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