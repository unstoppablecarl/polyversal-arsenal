<?php

namespace App\Services\Tile;

use App\Models\Tile;
use App\Services\Support\Base64ImageParser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;

class TileImage
{
    protected $dir = 'tile-images';

    public function saveFrontImage(Tile $tile, $data)
    {
        $this->deleteFront($tile);
        $images = $this->saveImage($tile, 'front', $data);

        return [
            'front_image' => $images['image'],
            'front_thumb' => $images['thumb'],
        ];
    }

    public function saveBackImage(Tile $tile, $data)
    {
        $this->deleteBack($tile);
        $images = $this->saveImage($tile, 'back', $data);

        return [
            'back_image' => $images['image'],
            'back_thumb' => $images['thumb'],
        ];
    }

    public function saveFrontSourceImage(Tile $tile, $data)
    {
        $this->deleteFrontSourceImage($tile);
        $image = $this->saveSourceImage($tile, 'front-source', $data);

        return [
            'front_source_image' => $image,
        ];
    }

    public function saveBackSourceImage(Tile $tile, $data)
    {
        $this->deleteBackSourceImage($tile);
        $image = $this->saveSourceImage($tile, 'back-source', $data);

        return [
            'back_source_image' => $image,
        ];
    }

    public function saveFrontSvg(Tile $tile, $data)
    {
        $timestamp = Carbon::now()->getTimestamp();
        $fileName  = 'front-' . $tile->id . '-' . $timestamp . '.svg';

        $this->deleteFrontSvg($tile);
        Storage::disk('local')->put($this->local($fileName), $data);

        return [
            'front_svg' => $fileName,
        ];
    }

    public function saveBackSvg(Tile $tile, $data)
    {
        $timestamp = Carbon::now()->getTimestamp();
        $fileName  = 'back-' . $tile->id . '-' . $timestamp . '.svg';

        $this->deleteBackSvg($tile);
        Storage::disk('local')->put($this->local($fileName), $data);

        return [
            'back_svg' => $fileName,
        ];
    }

    public function deleteAllImages(Tile $tile)
    {
        $this->deleteFrontSourceImage($tile);
        $this->deleteFront($tile);

        $this->deleteBackSourceImage($tile);
        $this->deleteBack($tile);

        $this->deleteFrontSvg($tile);
        $this->deleteBackSvg($tile);
    }

    public function deleteFrontSvg(Tile $tile)
    {
        $this->deleteFile($tile->front_svg);
    }

    public function deleteBackSvg(Tile $tile)
    {
        $this->deleteFile($tile->back_svg);
    }

    public function deleteFront(Tile $tile)
    {
        $this->deleteFile($tile->front_image);
        $this->deleteFile($tile->front_thumb);
    }

    public function deleteBack(Tile $tile)
    {
        $this->deleteFile($tile->back_image);
        $this->deleteFile($tile->back_thumb);
    }

    public function deleteFrontSourceImage(Tile $tile)
    {
        $this->deleteFile($tile->front_source_image);
    }

    public function deleteBackSourceImage(Tile $tile)
    {
        $this->deleteFile($tile->back_source_image);
    }

    protected function deleteFile($file)
    {
        if ($file) {
            Storage::disk('local')->delete($this->local($file));
        }
    }

    protected function saveSourceImage(Tile $tile, $prefix, $data)
    {
        $base64ImageParser = new Base64ImageParser;
        $extension         = $base64ImageParser->extension($data);
        $timestamp         = Carbon::now()->getTimestamp();

        $prefix   = Str::finish($prefix, '-');
        $fileName = $prefix . $tile->id . '-' . $timestamp . '.' . $extension;

        $file = $this->path($fileName);

        Image::make($data)
            ->fit(1041, 902, function (Constraint $constraint) {
                $constraint->upsize();
            })
            ->save($file);

        return $fileName;
    }

    protected function saveImage(Tile $tile, $prefix, $data)
    {
        $base64ImageParser = new Base64ImageParser;
        $extension         = $base64ImageParser->extension($data);
        $timestamp         = Carbon::now()->getTimestamp();

        $prefix        = Str::finish($prefix, '-');
        $fileName      = $prefix . $tile->id . '-' . $timestamp . '.' . $extension;
        $thumbFileName = $prefix . 'thumb-' . $tile->id . '-' . $timestamp . '.' . $extension;

        $file      = $this->path($fileName);
        $thumbFile = $this->path($thumbFileName);

        $img = Image::make($data)
            ->fit(1041, 902, function (Constraint $constraint) {
                $constraint->upsize();
            })
            ->save($file);

        $img->fit(251, 218, function (Constraint $constraint) {
            $constraint->upsize();
        })
            ->save($thumbFile);

        return [
            'image' => $fileName,
            'thumb' => $thumbFileName,
        ];
    }

    public function path($file = '')
    {
        return storage_path('app/' . $this->localPath()) . $this->prefix($file);
    }

    public function localPath()
    {
        return 'public/' . $this->dir;
    }

    public function urlPath()
    {
        return '/storage/' . $this->dir;
    }

    public function url($file = '')
    {
        $file = $this->prefix($file);
        return $this->urlPath() . $file;
    }

    public function local($file = '')
    {
        $file = $this->prefix($file);
        return $this->localPath() . $file;
    }

    protected function prefix($file)
    {
        if ($file) {
            $file = DIRECTORY_SEPARATOR . $file;
        }
        return $file;
    }
}
