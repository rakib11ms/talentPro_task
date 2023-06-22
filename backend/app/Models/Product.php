<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;
class Product extends Model
{
    use HasFactory;
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
