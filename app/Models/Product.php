<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['category_id', 'photo', 'name', 'price', 'stock', 'exp_date'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
