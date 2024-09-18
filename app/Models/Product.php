<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'image',
        'stock',
        'price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
