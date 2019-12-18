<?php

namespace App\Services\Exceptions;

use Exception;
use Throwable;

class InvalidTileListSortableField extends Exception
{
    public function __construct(
        string $field,
        int $code = 0,
        Throwable $previous = null
    )
    {
        $message = 'Invalid Tile list sort field: ' . $field;
        parent::__construct($message, $code, $previous);
    }
}
