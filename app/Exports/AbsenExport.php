<?php

namespace App\Exports;

use App\Enums\StatusAbsenEnum;
use App\Models\Absensi;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AbsenExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, ShouldAutoSize, WithEvents
{
    protected $absens;

    public function __construct($absens)
    {
        $this->absens = $absens;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->absens);
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama',
            'NRP/NIP',
            'Jabatan',
            'Divisi',
            'Tanggal',
            'Waktu Masuk',
            'Waktu Pulang',
            'Foto Masuk',
            'Foto Pulang',
            'Status',
            'Lokasi'
        ];
    }

    public function map($absen): array
    {
        static $no = 1;

        return [
            $no++,
            $absen->user->name,
            $absen->user->nrp,
            $absen->user->jabatan,
            $absen->user->divisi,
            $absen->created_at->format('d-m-Y'),
            Carbon::parse($absen->waktu_masuk)->format('H:i'),
            Carbon::parse($absen->waktu_keluar)->format('H:i'),
            '',
            '',
            StatusAbsenEnum::getTextStatus($absen->status),
            '=HYPERLINK("'.$this->formattedLinkGoogleMap($absen->latitude1, $absen->longitude1, $absen->latitude2, $absen->longitude2).'")'
        ];
    }

    private function formattedLinkGoogleMap($lat1, $lng1, $lat2 = null, $lng2 = null)
    {
        if($lat2 && $lng2){
            return "https://www.google.com/maps/dir/${lat1},${lng1}/${lat2},${lng2}";
        }
        
        return "https://www.google.com/maps?q=${lat1},${lng1}";
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2; // mulai dari row ke-2

        foreach ($this->absens as $absen) {

            // 📸 FOTO MASUK (kolom I)
            if ($absen->image_masuk) {
                $drawing = new Drawing();
                $drawing->setName('Foto Masuk');
                $drawing->setDescription('Foto Masuk');
                $drawing->setPath(public_path('storage/' . $absen->image_masuk));
                $drawing->setHeight(80);
                $drawing->setCoordinates('I' . $row);

                $drawings[] = $drawing;
            }

            // 📸 FOTO PULANG (kolom J)
            if ($absen->image_keluar) {
                $drawing2 = new Drawing();
                $drawing2->setName('Foto Pulang');
                $drawing2->setDescription('Foto Pulang');
                $drawing2->setPath(public_path('storage/' . $absen->image_keluar));
                $drawing2->setHeight(80);
                $drawing2->setCoordinates('J' . $row);

                $drawings[] = $drawing2;
            }

            $row++;
        }

        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $totalRow = count($this->absens) + 1;

                // ✅ 1. Wrap text semua kolom
                $sheet->getStyle('A1:L' . $totalRow)
                    ->getAlignment()
                    ->setWrapText(true);

                // ✅ 2. Vertical align biar rapi tengah
                $sheet->getStyle('A1:L' . $totalRow)
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // ✅ 3. Tinggi row menyesuaikan gambar
                foreach (range(2, $totalRow) as $row) {
                    $sheet->getRowDimension($row)->setRowHeight(80);
                }

                // ✅ 4. Khusus kolom gambar (biar tidak gepeng)
                $sheet->getColumnDimension('I')->setWidth(20);
                $sheet->getColumnDimension('J')->setWidth(20);

                // ✅ 5. Header bold + center
                $sheet->getStyle('A1:L1')->getFont()->setBold(true);
                $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
