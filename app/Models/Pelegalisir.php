<?php

namespace App\Models;

use App\Models\Legalisir;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelegalisir extends Model
{
    use HasFactory;

    protected $table = 'pelegalisir';
    protected $fillable = [
        'nidn',
        'nama',
        'jabatan'
    ];

    public function legalisir()
    {
        return $this->hasMany(Legalisir::class, 'pelegalisir_id');
    }

    public function getJabatanAttribute($val)
    {
        return ucwords($val);
    }
}
