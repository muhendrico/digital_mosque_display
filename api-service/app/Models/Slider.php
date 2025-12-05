<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'image_path', 'type', 'extra_data', 'order', 'is_active'];
    protected $casts = ['extra_data' => 'array'];
}