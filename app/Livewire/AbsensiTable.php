<?php

namespace App\Livewire;

use App\Enums\StatusAbsenEnum;
use App\Models\Absensi;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class AbsensiTable extends DataTableComponent
{
    public $tanggal = null;
    public $status = null;
    public $searchNama = null;

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
    public function setFilterAbsensi($tanggal, $status, $search)
    {
        $this->tanggal = $tanggal ?? null;
        $this->status = $status ?? null;
        $this->searchNama = $search ?? null;

        $this->resetPage(); // penting biar balik ke page 1
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Absensi::query()
            ->with('user')
            ->select('absensis.*')
            ->when($this->tanggal, function ($query) {
                $query->whereDate('absensis.created_at', $this->tanggal);
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
                ->format(fn($row) => Carbon::parse($row)->format('H:i'))
                ->sortable(),
            Column::make("Waktu Pulang", "waktu_keluar")
                ->format(fn($row) => Carbon::parse($row)->format('H:i'))
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
