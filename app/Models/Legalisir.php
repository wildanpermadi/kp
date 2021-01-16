<?php

namespace App\Models;

use App\Models\Pelegalisir;
use App\Models\PermintaanLegalisir;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Legalisir extends Model
{
    use HasFactory;

    protected $table = 'legalisir';
    protected $fillable = [
        'pelegalisir_id',
        'permintaan_legalisir_id',
        'file_ijazah_legalisir',
        'berlaku_sampai',
        'kode_legalisir',
        'qrcode'
    ];
    protected $dates = [
        'berlaku_sampai',
        'created_at'
    ];

    public function permintaanLegalisir()
    {
        return $this->belongsTo(PermintaanLegalisir::class, 'permintaan_legalisir_id');
    }

    public function pelegalisir()
    {
        return $this->belongsTo(Pelegalisir::class, 'pelegalisir_id');
    }
}
