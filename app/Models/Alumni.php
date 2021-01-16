<?php

namespace App\Models;

use App\Models\User;
use App\Models\PermintaanLegalisir;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';
    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'tgl_lahir',
        'email',
        'prodi',
        'sk_kelulusan',
        'tgl_kelulusan'
    ];
    protected $dates = [
        'tgl_lahir',
        'tgl_kelulusan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function permintaanLegalisir()
    {
        return $this->hasOne(PermintaanLegalisir::class, 'alumni_id');
    }
}
