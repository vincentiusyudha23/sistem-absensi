<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Absensi;

class AbsensiTable extends DataTableComponent
{
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

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        $absensi = Absensi::query();
            
        return $absensi;
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("User id", "user_id")
                ->sortable(),
            Column::make("Waktu Hadir", "waktu_masuk")
                ->sortable(),
            Column::make("Waktu keluar", "waktu_keluar")
                ->sortable(),
            Column::make("Latitude", "latitude")
                ->sortable(),
            Column::make("Longitude", "longitude")
                ->sortable(),
            Column::make("Address", "address")
                ->sortable(),
            Column::make("Image", "image")
                ->sortable(),
            Column::make("Status", "status")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
