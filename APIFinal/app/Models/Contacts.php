<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- Baris ini yang dikoreksi

class Contacts extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_name',
        'sender_email',
        'sender_phone',
        'subject',
        'message_content',
    ];

    /**
     * Get the user that sent the contact message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}