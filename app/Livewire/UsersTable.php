<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class UsersTable extends DataTableComponent
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
        $users = User::query()->select('*')->where('role', 'user');
            
        return $users;
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "name")
                ->sortable()
                ->searchable(),
            Column::make("NRP/NIP", "nrp")
                ->sortable()
                ->searchable(),
            Column::make("Divisi", "divisi")
                ->sortable()
                ->searchable(),
            Column::make("Jabatan", "jabatan")
                ->sortable()
                ->searchable(),
            Column::make("Status", "status")
                ->format(fn($value) => $value
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Tidak Aktif</span>'
                )
                ->html(),
            ButtonGroupColumn::make('Aksi')
                ->buttons([
                    LinkColumn::make('Detail')
                        ->title(fn($row) => 'Detail')
                        ->location(fn($row) => route('admin.detail_anggota', ['id' => $row->id]))
                        ->attributes(function($row) {
                            return [
                                'type' => 'button',
                                'class' => 'btn btn-sm btn-success fw-semibold',
                                'data-id' => $row->id
                            ];
                        }),
                ])
        ];
    }

    #[On('setCustomSearch')]
    public function setCustomSearch($value)
    {
        $this->setSearch($value);
    }
}
