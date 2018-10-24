<?php

namespace App\Services\Support\StaticDBData\Exceptions;

use Exception;
use Throwable;

class StaticDBDataNotFoundException extends Exception
{
    protected $staticDBDataClass;

    public function __construct(
        string $staticDBDataClass,
        $value,
        string $key = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        if (!$key) {
            $key = $this->toType($value);
        }
        $message = $this->makeMessage($key, $value, $staticDBDataClass);

        parent::__construct($message, $code, $previous);
    }

    protected function toType($value)
    {
        if (is_string($value)) {
            return 'name';
        }

        if (is_object($value)) {
            return 'object';
        }

        return 'id';
    }

    protected function makeMessage($key, $value, $staticDBDataClass): string
    {
        if ($key == 'object') {
            $key   = 'invalid object';
            $value = get_class($value) . ' ' . $value;
        } else {
            $value = "'{$value}'";
        }
        return "{$key}: {$value}, not found in StaticDBData class: {$staticDBDataClass}.";
    }

}
