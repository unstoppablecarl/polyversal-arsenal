<?php

namespace App\Services\Support;

class Base64ImageParser
{
    public function parse($value): array
    {
        $explode = explode(',', $value);
        $format  = str_replace(
            [
                'data:image/',
                ';',
                'base64',
            ],
            [
                '', '', '',
            ],
            $explode[0]
        );

        $payload = $explode[1];
        return [$format, $payload];
    }

    public function extension($value)
    {
        return $this->parse($value)[0];
    }
}
