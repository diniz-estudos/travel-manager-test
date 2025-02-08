<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'solicitante',
        'destino',
        'data_ida',
        'data_volta',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
