<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;
    
    protected $table = 'absensis';
    protected $fillable = [
        'user_id',
        'waktu_masuk',
        'waktu_keluar',
        'latitude1',
        'longitude1',
        'address1',
        'latitude2',
        'longitude2',
        'address2',
        'image_masuk',
        'image_keluar',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
