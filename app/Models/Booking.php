<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'name', 'phone', 'booking_date', 
        'number_of_people', 'status', 'special_request',
        'booking_type', 'total_amount', 'payment_status',
        'full_payment_time'
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'full_payment_time' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookingMenus()
    {
        return $this->hasMany(BookingMenu::class);
    }

    public function getTotalAmountAttribute()
    {
        return $this->bookingMenus->sum('subtotal');
    }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'info'
        ][$this->status] ?? 'secondary';
    }

    public function getCanEditAttribute()
    {
        // Chỉ cho phép sửa khi đơn đang ở trạng thái chờ xác nhận
        // và thời gian đặt bàn còn cách ít nhất 24 tiếng
        return $this->status === 'pending' 
            && $this->booking_date->diffInHours(now()) >= 24;
    }

    public function getCanCancelAttribute()
    {
        // Cho phép hủy khi đơn đang chờ xác nhận
        return $this->status === 'pending';
    }

    public function getIsPaymentPendingAttribute()
    {
        return $this->payment_status === 'pending';
    }

    public function getIsPaymentProcessingAttribute()
    {
        return $this->payment_status === 'processing';
    }

    public function getIsPaymentPaidAttribute()
    {
        return $this->payment_status === 'paid';
    }

    public function getIsPaymentFailedAttribute()
    {
        return $this->payment_status === 'failed';
    }
} 