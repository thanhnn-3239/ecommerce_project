<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Image;
trait HandleImageTrait
{
    protected $path = 'upload/users/';
    public function verify($request)
    {
        return $request->has('image');
    }

    public function saveImage($request)
    {
        if ($this->verify($request)) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save($this->path . $name);
            return $name;
        }
    }

    public function updateImage($request, $currentImage)
    {
        if($this->verify($request))
        {
            $this->deleteImage($currentImage);
            return $this->saveImage($request);
        }
        return $currentImage;
    }

    public function deleteImage($imageName): void
    {
        if($imageName && file_exists($this->path .$imageName))
        {
            Storage::delete($this->path .$imageName);
        }
    }
}