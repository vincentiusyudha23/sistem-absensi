<?php

namespace App\Jobs;

use App\Models\Absensi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;

class ProcessAbsensiImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $image;
    public $absensiId;
    public $type;

    public function __construct($image, $absensiId, $type)
    {
        $this->image = $image;
        $this->absensiId = $absensiId;
        $this->type = match($type){
            1 => 'masuk',
            2 => 'keluar',
            default => ''
        };
    }

    public function handle()
    {
        try {
            if (!$this->image || !$this->absensiId) {
                throw new \Exception('Data image atau absensiId kosong');
            }

            // 1. Bersihkan string Base64 (lebih fleksibel untuk berbagai format)
            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $this->image);
            $decodedImage = base64_decode($imageData);

            if (!$decodedImage) {
                throw new \Exception('Gagal decode base64 image');
            }

            // 2. Proses Image dengan Sintaks V3
            // read() menggantikan make()
            $img = Image::read($decodedImage);

            // scale() lebih simpel untuk resize proposional dibanding resize() lama
            $img->scale(width: 640); 

            // toJpeg() menggantikan encode('jpg')
            $encoded = $img->toJpeg(70);

            // 3. Nama file & Path
            $imageName = now()->timestamp . '_' . uniqid() . '.jpg';
            $path = 'absensi/' . $imageName;

            // 4. Simpan ke Storage
            // Gunakan toString() atau (string) agar data binary bisa dibaca oleh Storage
            Storage::disk('public')->put($path, $encoded->toString());

            // 5. Update DB
            // Catatan: Biasanya simpan PATH-nya saja, bukan Full URL agar lebih fleksibel
            Absensi::where('id', $this->absensiId)
                ->update(['image_' . $this->type => $path]);

        } catch (\Throwable $e) {
            Log::error('Gagal proses image absensi', [
                'absensi_id' => $this->absensiId,
                'error' => $e->getMessage(),
            ]);

            Absensi::where('id', $this->absensiId)->update(['image_' . $this->type => null]);
            // Lempar error agar Job bisa dicoba ulang (retry) oleh Laravel Queue
            throw $e; 
        }
    }
}