@extends('master')

@section('content')
    <section>
        <div class="w-100">
            <h2 class="fw-bold mb-1">Daftar Anggota</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Anggota</li>
                </ol>
            </nav>
        </div>

        <div class="card mb-3">
            <div class="card-body w-100">
                <div class="row g-1">
                    <div class="col-8">
                        <div class="input-group w-100">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="bi bi-search"></i>
                            </span>
                            <input class="form-control" type="text" placeholder="Pencarian..." id="searchInput">
                        </div>
                    </div>

                    <div class="col-4">
                        <a href="{{ route('admin.tambah_anggota') }}" class="btn btn-success text-center w-100 fw-semibold">
                            + Tambah Anggota
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="fw-bold mb-2">Daftar Anggota</h4>
                <div class="table-responsive">
                    <livewire:users-table/>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('searchInput');

            let timeout = null;

            input.addEventListener('keyup', function () {
                clearTimeout(timeout);

                timeout = setTimeout(() => {
                    Livewire.dispatch('setCustomSearch', {
                        value: input.value
                    });
                }, 500); // debounce 500ms
            });
        });
    </script>
@endsection