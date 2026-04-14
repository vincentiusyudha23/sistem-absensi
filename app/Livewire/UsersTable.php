<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
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
        $users = User::query()->where('role', 'user');
            
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
            Column::make("Aksi", "id")
                ->format(function($value, $row, Column $column){
                    $route = route('admin.edit_anggota', ['id' => $row]);
                    return '
                        <div class="d-flex flex-wrap gap-1">
                            <a href='.$route.' class="btn btn-info btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </div>
                    ';
                })
                ->html(),
        ];
    }

    #[On('setCustomSearch')]
    public function setCustomSearch($value)
    {
        $this->setSearch($value);
    }
}
