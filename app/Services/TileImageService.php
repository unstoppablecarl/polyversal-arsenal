<?php

namespace App\Services;

use App\Models\Tile;
use App\Services\Support\Base64ImageParser;
use Illuminate\Support\Facades\Storage;

class TileImageService
{
    protected $dir = 'tile-images';

    /**
     * @var Base64ImageParser
     */
    protected $base64ImageParser;

    public function saveFront(Tile $tile, $data)
    {
        $base64ImageParser = new Base64ImageParser;
        list($extension, $payload) = $base64ImageParser->parse($data);

        $frontFileName = $this->frontFileName($tile->id, $extension);
        $file          = $this->localPath() . '/' . $frontFileName;

        Storage::disk('local')->put($file, $payload);

        $tile->front_image_file = $frontFileName;
        $tile->save();

        $url = $this->urlPath() . '/' . $frontFileName;
        return $url;
    }

    public function frontFileName($tileId, $extension)
    {
        return 'tile-front-' . $tileId . '.' . $extension;
    }

    // public function backFileName($tileId, $extension)
    // {
    //     return 'tile-back-' . $tileId . '.' . $extension;
    // }
    //
    // public function frontUrl($tileId, $extension)
    // {
    //     $file = $this->frontFileName($tileId, $extension);
    //     return asset($this->publicPath() . '/' . $file);
    // }

    public function localPath()
    {
        return "public/{$this->dir}";
    }

    public function urlPath()
    {
        return "storage/{$this->dir}";
    }
}
