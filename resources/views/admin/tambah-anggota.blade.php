@extends('master')

@section('content')
<section class="container">
    <div class="w-100 mb-3">
        <h2 class="fw-bold mb-1">Tambah Anggota</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.list_users') }}">Daftar Anggota</a>
                </li>
                <li class="breadcrumb-item active">Tambah Anggota</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.tambah_anggota.store') }}" method="POST">
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
                            value="{{ old('name') }}"
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
                            value="{{ old('nrp') }}"
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
                            value="{{ old('jabatan') }}"
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