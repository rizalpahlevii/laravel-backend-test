<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'file',
        'enable'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_images', 'image_id', 'product_id');
    }

    public function getImageUrlAttribute(): string
    {
        // Get full url from domain to file
        return Storage::disk('public')->url('images/' . $this->file);
    }
}
