<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_code',
        'name',
        'slug',
        'description',
        'image',
        'status',
        
    ];

    public function menu()
    {
        return $this->hasMany(Menu::class, 'category_code', 'category_code');
    }
} 