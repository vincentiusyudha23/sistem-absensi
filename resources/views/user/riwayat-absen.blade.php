@extends('master')

@section('content')
    <section>
        <div class="w-100">
            <h2 class="fw-bold mb-1">Riwayat Absensi</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Riwayat Absensi</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div class="card-body">
                <livewire:riwayat-absen-table/>
            </div>
        </div>
    </section>
@endsection