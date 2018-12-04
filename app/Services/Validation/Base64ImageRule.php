<?php

namespace App\Services\Validation;

use App\Services\Support\Base64ImageParser;
use Illuminate\Contracts\Validation\Rule;

class Base64ImageRule implements Rule
{
    protected $validFileFormats = [];
    protected $fileFormatInvalid = false;
    protected $payloadInvalid = false;

    public function __construct(array $validFileFormats = ['png', 'jpg', 'svg'])
    {
        $this->validFileFormats = $validFileFormats;
    }

    public function passes($attribute, $value)
    {
        list($format, $payload) = (new Base64ImageParser())->parse($value);
        if (!$this->isValidFileFormat($format)) {
            $this->fileFormatInvalid = true;
            return false;
        }
        if (!$this->isValidPayload($payload)) {
            $this->payloadInvalid = true;
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        if (!$this->fileFormatInvalid) {
            $values = join(",", $this->validFileFormats);
            return "The :attribute must be a file of type: {$values}.";
        }

        if (!$this->payloadInvalid) {
            return "The :attribute was not encoded correctly.";
        }
    }

    protected function isValidFileFormat($format): bool
    {
        return in_array($format, $this->validFileFormats);
    }

    protected function isValidPayload($payload): bool
    {
        return preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $payload);
    }

}
