<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'reservation_datetime',
        'number_of_guests',
        'area_preference',
        // Hapus 'status' dari sini: 'status',
    ];

    protected function casts(): array
    {
        return [
            'reservation_datetime' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}