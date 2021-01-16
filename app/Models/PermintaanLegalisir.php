<?php

namespace App\Models;

use App\Models\Alumni;
use App\Models\Legalisir;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermintaanLegalisir extends Model
{
    use HasFactory;

    protected $table = 'permintaan_legalisir';
    protected $fillable = [
        'alumni_id',
        'file_ijazah',
        'no_ijazah',
        'status'
    ];
    protected $dates = [
        'created_at'
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }

    public function legalisir()
    {
        return $this->hasOne(Legalisir::class, 'permintaan_legalisir_id');
    }

    public function getStatusAttribute($val)
    {
        return ucwords($val);
    }
}
