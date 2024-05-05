<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class DocumentRepository
{

    public function uploadImage($model, $request, $storagePath)
    {
        // $existingImage = $model->image();
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store($storagePath);
        //     $imageName = $request->file('image')->getClientOriginalName();
        //     $imageUrl = Storage::url($imagePath);
        //     if ($existingImage) {
        //         $oldImagePath = str_replace('storage/', 'public/', $existingImage->url);
        //         Storage::delete($oldImagePath);
        //         $existingImage->update(['url' => $imageUrl, 'name' => $imageName]);
        //     } else {
        //         $image = new Image([
        //             'url' => $imageUrl,
        //             'name' => $imageName,
        //             'default' => 1,
        //         ]);
        //         $model->images()->save($image);
        //     }
        // }
        // return true;
    }
}

