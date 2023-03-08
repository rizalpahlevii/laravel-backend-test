<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'enable',
    ];

    public function categoryProducts(): HasMany
    {
        return $this->hasMany(CategoryProduct::class, 'category_id', 'id');
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, CategoryProduct::class, 'category_id', 'id', 'id', 'product_id');
    }

    public function getEnableDescriptionAttribute(): string
    {
        return $this->enable ? 'Active' : 'Inactive';
    }
}
