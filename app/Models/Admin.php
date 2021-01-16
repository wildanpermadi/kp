<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $fillable = [
        'user_id',
        'nama'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
