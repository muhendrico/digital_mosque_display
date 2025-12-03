<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    // Karena tabel kita isinya key-value, kita tidak butuh timestamp updated_at otomatis di setiap baris (opsional)
    protected $fillable = ['key', 'value'];
}