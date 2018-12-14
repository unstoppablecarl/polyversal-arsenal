<?php

namespace App\Services;

class Base64ImageSaver
{

    /**
     * @var Base64ImageParser
     */
    protected $base64ImageParser;

    public function save()
    {
        list($extension, $payload) = $base64ImageParser->parse($data);

        $frontFileName = $this->frontFileName($tile->id, $extension);
        $file          = $this->localPath() . '/' . $frontFileName;

        Storage::disk('local')->put($file, $payload);
    }
}
