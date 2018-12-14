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

    public function saveFrontSourceImage(Tile $tile, $data)
    {
        $image = $this->saveSourceImage($tile, 'front-source', $data);

        Storage::disk('local')->delete($this->local($tile->front_source_image));

        return [
            'front_source_image' => $image,
        ];
    }

    public function saveBackSourceImage(Tile $tile, $data)
    {
        $image = $this->saveSourceImage($tile, 'back-source', $data);

        Storage::disk('local')->delete($this->local($tile->back_source_image));

        return [
            'back_source_image' => $image,
        ];
    }

    public function saveFrontSvg(Tile $tile, $data)
    {
        $base64ImageParser = new Base64ImageParser;
        list($extension, $payload) = $base64ImageParser->parse($data);

        $timestamp = Carbon::now()->getTimestamp();
        $fileName  = 'svg-' . $tile->id . '-' . $timestamp . '.' . $extension;

        Storage::disk('local')->put($this->local($fileName), $payload);

        Storage::disk('local')->delete($this->local($tile->front_svg));

        return [
            'front_svg' => $fileName,
        ];
    }

    public function deleteAllImages(Tile $tile)
    {
        Storage::disk('local')->delete($this->local($tile->front_image));
        Storage::disk('local')->delete($this->local($tile->front_thumb));

        Storage::disk('local')->delete($this->local($tile->back_image));
        Storage::disk('local')->delete($this->local($tile->back_thumb));

        Storage::disk('local')->delete($this->local($tile->front_svg));
        Storage::disk('local')->delete($this->local($tile->back_svg));
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

    protected function saveImage(Tile $tile, $prefix, $data, $generateThumb = false)
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

        if ($generateThumb) {
            $img->fit(251, 218, function (Constraint $constraint) {
                $constraint->upsize();
            })
                ->save($thumbFile);
        }

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
