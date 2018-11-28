<?php

namespace App\Services\Exceptions;

use App\Models\Ability;
use App\Services\TileTypes;
use Exception;
use Throwable;

class InvalidAbilityTileTypeException extends Exception
{
    public function __construct(
        Ability $ability,
        $tileTypeId,
        int $code = 0,
        Throwable $previous = null
    ) {
        $tileTypeDisplayName = $this->tileTypes()->idToDisplayNameOrFail($tileTypeId);
        $message             = 'The ' . $ability->display_name . ' ability cannot use tile type: ' . $tileTypeDisplayName;
        parent::__construct($message, $code, $previous);
    }

    protected function tileTypes(): TileTypes
    {
        return app(TileTypes::class);
    }
}
