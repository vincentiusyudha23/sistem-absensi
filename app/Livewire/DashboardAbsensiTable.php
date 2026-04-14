<?php

namespace App\Livewire;

use App\Enums\StatusAbsenEnum;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class DashboardAbsensiTable extends DataTableComponent
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
        $absensi = Absensi::query()
            ->with('user')
            ->whereDate('absensis.created_at', Carbon::now());
            
        return $absensi;
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "user.name")
                ->searchable()
                ->sortable(),
            Column::make("Divisi", "user.divisi")
                ->searchable()
                ->sortable(),
            Column::make("Check-in", "waktu_masuk")
                ->format(fn($row) => Carbon::parse($row)->format('H:i'))
                ->sortable(),
            Column::make("Check-Out", "waktu_keluar")
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
                        ->location(fn($row) => '#')
                        ->attributes(function($row) {
                            return [
                                'class' => 'btn btn-sm btn-success',
                            ];
                        }),
                ])
        ];
    }
}
