<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'menu_id', 'menu_name_at_order', 'price_at_order', 'quantity', 'subtotal'];
    public function order() { return $this->belongsTo(Order::class, 'order_id'); }
    public function menu() { return $this->belongsTo(Menu::class, 'menu_id'); }
}