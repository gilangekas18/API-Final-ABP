<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // Tambahkan baris ini
    protected $table = 'menu';

    protected $fillable = ['category_id', 'name', 'price'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}