<?php

namespace App\Livewire;

use App\Enums\StatusAbsenEnum;
use App\Exports\AbsenExport;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class AbsensiTable extends DataTableComponent
{
    public $tanggal_min = null;
    public $tanggal_max = null;
    public $status = null;
    public $searchNama = null;
    public $userId = null;

    public function __construct($userId = null)
    {
        $this->userId = $userId ?? null;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setFiltersEnabled() // Aktifkan filters
            ->setFilterLayout('slide-down') // Atau 'slide-down'
            ->setFilterPillsEnabled()
            ->setColumnSelectDisabled()
            ->setSearchVisibilityDisabled()
            ->setPerPageVisibilityDisabled()
            ->setEmptyMessage('Tidak ada data');
    }

    #[On('setFilterAbsensi')]
    public function setFilterAbsensi($tanggal_min, $tanggal_max, $status, $search)
    {
        $this->tanggal_min = $tanggal_min ?? null;
        $this->tanggal_max = $tanggal_max ?? $tanggal_min ?? null;
        $this->status = $status ?? null;
        $this->searchNama = $search ?? null;

        $this->resetPage(); // penting biar balik ke page 1
        
        $absens = $this->builder()->get();

        $tepat_waktu = $absens->filter(fn($a) => $a->status == 1)->count();
        $terlambat   = $absens->filter(fn($a) => $a->status == 2)->count();
        $tidak_hadir = $absens->filter(fn($a) => $a->status == 3)->count();

        $this->js('
            let tepatWaktu = document.getElementById("text-tepatWaktu");
            let terlambat = document.getElementById("text-terlambat");
            let tidakHadir = document.getElementById("text-tidakHadir");

            tepatWaktu.innerText = '.$tepat_waktu.';
            terlambat.innerText = '.$terlambat.';
            tidakHadir.innerText = '.$tidak_hadir.';
        ');
    }

    #[On('exportData')]
    public function export()
    {
        $absens = $this->builder()->get();
        $dateNow = Carbon::now()->format('dmY');
        return Excel::download(new AbsenExport($absens), 'absen_' . $dateNow .'.xlsx');
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Absensi::query()
            ->with('user')
            ->select('absensis.*')
            ->when(!empty($this->userId), function($query){
                $query->where('user_id', $this->userId);
            })
            ->when(!empty($this->tanggal_min) || !empty($this->tanggal_max), function ($query) {
                $query->whereDate('absensis.created_at', '>=' ,$this->tanggal_min)
                    ->whereDate('absensis.created_at', '<=' ,$this->tanggal_max);
            })
            ->when($this->status, function ($query) {
                $query->where('absensis.status', $this->status);
            })
            ->when($this->searchNama, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchNama . '%')
                    ->orWhere('nrp', 'like', '%' . $this->searchNama . '%');
                });
            });
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "user.name")
                ->searchable()
                ->sortable(),
            Column::make("NRP/NIP", "user.nrp")
                ->searchable()
                ->sortable(),
            Column::make("Divisi", "user.divisi")
                ->searchable()
                ->sortable(),
            Column::make("Tanggal", "created_at")
                ->format(fn($row) => Carbon::parse($row)->translatedFormat('l, d-m-Y'))
                ->searchable()
                ->sortable(),
            Column::make("Waktu Masuk", "waktu_masuk")
                ->format(fn($row) => $row ? Carbon::parse($row)->format('H:i') : '')
                ->sortable(),
            Column::make("Waktu Pulang", "waktu_keluar")
                ->format(fn($row) => $row ? Carbon::parse($row)->format('H:i') : '')
                ->sortable(),
            Column::make("Status", "status")
                ->format(fn($row) => StatusAbsenEnum::getBadgeStatusAbsen($row))
                ->html()
                ->sortable(),
            ButtonGroupColumn::make('Aksi')
                ->buttons([
                    LinkColumn::make('Detail')
                        ->title(fn($row) => 'Detail')
                        ->location(fn($row) => 'javascript:void(0)')
                        ->attributes(function($row) {
                            return [
                                'type' => 'button',
                                'class' => 'btn btn-sm btn-success btn-detail',
                                'data-id' => $row->id
                            ];
                        }),
                ])
        ];
    }
}
