<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Storage;

class ImageUploadService
{
    /**
     * Handle the image upload
     *
     * @param UploadedFile $file
     * @param Image|null $image
     * @return Image
     */
    public function handle(UploadedFile $file, Image $image = null): Image
    {
        $name = $file->getClientOriginalName();
        $path = $file->store('images', 'public');
        if ($image) {
            Storage::disk('public')->delete($image->file);
        }

        $image = $image ?? new Image();
        $image->name = $name;
        $image->file = $path;
        $image->enable = true;
        $image->save();
        return $image->fresh();
    }
}
