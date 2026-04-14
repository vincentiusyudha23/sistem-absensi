@extends('master')

@section('content')
    <section class="container">
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
                        <small class="text-muted me-1">Hari Ini</small>
                    </div>

                    <h1 class="mx-3" style="font-weight: 900;">
                        75
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
                        <small class="text-muted me-1">Hari Ini</small>
                    </div>

                    <h1 class="mx-3" style="font-weight: 900;">
                        75
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
                        <small class="text-muted me-1">Hari Ini</small>
                    </div>

                    <h1 class="mx-3" style="font-weight: 900;">
                        75
                    </h1>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-1">
                    <div class="col-md-3 col-12">
                        <label class="form-label mb-1 fw-semibold">Filter Tanggal</label>
                        <div class="input-group">
                            <input class="form-control" type="date">
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <label class="form-label mb-1 fw-semibold">Filter Divisi</label>
                        <div class="input-group">
                            <select class="form-select">
                                <option value="">-- Pilih Divisi --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <label class="form-label mb-1 fw-semibold">Filter Status</label>
                        <div class="input-group">
                            <select class="form-select">
                                <option value="">-- Pilih status --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <label class="form-label mb-1 fw-semibold">Cari Nama / NRP...</label>
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Cari nama / NRP...">
                        </div>
                    </div>
                    <div class="col-md-1 col-12 d-flex align-items-end">
                        <button type="button" class="btn btn-primary btn-sm w-100 py-2 fw-semibold">Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <livewire:absensi-table/>
            </div>
        </div>
    </section>
@endsection