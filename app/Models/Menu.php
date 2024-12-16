<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menu';
    
    protected $fillable = [
        'menu_code',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'category_code',
        'status',
        'position'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_code', 'category_code');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
