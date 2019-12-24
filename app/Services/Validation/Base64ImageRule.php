<?php

namespace App\Services\Validation;

use App\Services\Support\Base64ImageParser;
use Illuminate\Contracts\Validation\Rule;

class Base64ImageRule implements Rule
{
    protected $fileFormatInvalid = false;
    protected $payloadInvalid = false;
    protected $payloadInvalidSize = false;

    protected $invalidFormat;

    /**
     * @var string
     */
    private $maxSizeInMegaBytes;

    protected $validFileFormats = [];

    public function __construct(
        int $maxSizeInMegaBytes = 3,
        array $validFileFormats = ['png', 'jpg', 'jpeg']
    )
    {
        $this->maxSizeInMegaBytes = $maxSizeInMegaBytes;
        $this->validFileFormats   = $validFileFormats;
    }

    public function passes($attribute, $value)
    {
        list($format, $payload) = (new Base64ImageParser())->parse($value);
        if (!$this->isValidFileFormat($format)) {
            $this->fileFormatInvalid = true;
            $this->invalidFormat     = $format;
            return false;
        }
        if (!$this->isValidPayload($payload)) {
            $this->payloadInvalid = true;
            return false;
        }

        if (!$this->isValidPayloadSize($payload)) {
            $this->payloadInvalidSize = true;
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
        if ($this->fileFormatInvalid) {
            $values = join(",", $this->validFileFormats);
            return "The :attribute must be a file of type: {$values}. Type received: {$this->invalidFormat}";
        }

        if ($this->payloadInvalid) {
            return "The :attribute was not encoded correctly.";
        }

        if ($this->payloadInvalidSize) {
            return "The :attribute is too big. Max file size: {$this->maxSizeInMegaBytes} mb.";
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

    protected function isValidPayloadSize($payload): bool
    {
        $payload = base64_decode($payload, true);
        // using SI megabytes as that is what users will typically see in OS
        return strlen($payload) <= $this->maxSizeInMegaBytes * 1000000;
    }
}
