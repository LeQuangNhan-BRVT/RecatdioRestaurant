<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingMenu extends Model
{
    protected $fillable = [
        'booking_id',
        'menu_id',
        'quantity',
        'price',
        'subtotal'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
} 