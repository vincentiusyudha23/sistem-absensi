<?php

namespace App\Livewire;

use App\Enums\StatusAbsenEnum;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RiwayatAbsenTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setFiltersEnabled() // Aktifkan filters
            ->setFilterLayout('slide-down') // Atau 'slide-down'
            ->setFilterPillsEnabled()
            ->setColumnSelectDisabled()
            ->setSearchVisibilityDisabled()
            ->setEmptyMessage('Tidak ada data');
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        $absensi = Absensi::query()->where('user_id', $user->id);
            
        return $absensi;
    }

    public function columns(): array
    {
        return [
            Column::make("Tanggal", "created_at")
                ->format(function($row){
                    return Carbon::parse($row)->translatedFormat('l, d-m-Y');
                })
                ->sortable(),
            Column::make("Check-out", "waktu_masuk")
                ->format(function($row){
                    return Carbon::parse($row)->format('H:i');
                })
                ->sortable(),
            Column::make("Keterangan Check-in", "id")
                ->format(function($row){
                    $absen = Absensi::find($row);
                    $image = asset('storage/' . $absen->image_masuk);
                    if($absen->image_masuk){
                        return '
                            <div class="d-flex flex-column" style="max-width: 250px;">
                                <a class="text-center" href="'.$image.'">
                                    <img src="'.$image.'" width="100" height="auto">
                                </a>
                                <span class="text-wrap text-between">
                                    '.$absen->address1.'
                                </span>
                            </div>
                        ';
                    }
                    
                    return '';
                })
                ->html(),
            Column::make("Check-in", "waktu_keluar")
                ->format(function($row){
                    return Carbon::parse($row)->format('H:i');
                })
                ->sortable(),
            Column::make("Keterangan Check-out", "id")
                ->format(function($row){
                    $absen = Absensi::find($row);
                    $image = asset('storage/' . $absen->image_keluar);
                    if($absen->image_keluar){
                        return '
                            <div class="d-flex flex-column" style="max-width: 250px;">
                                <a class="text-center" href="'.$image.'">
                                    <img src="'.$image.'" width="100" height="auto">
                                </a>
                                <span class="text-wrap text-between">
                                    '.$absen->address2.'
                                </span>
                            </div>
                        ';
                    }
                    
                    return '';
                })
                ->html(),
            Column::make("Status", "status")
                ->format(function($row){
                    return StatusAbsenEnum::getBadgeStatusAbsen($row);
                })
                ->html(),
        ];
    }
}
