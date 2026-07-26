<?php
namespace App\Support\Json;

class JsonValidator
{
    /**
     * Validate if the given string is a valid JSON.
     *
     * @param string $json
     * @return bool
     */
    public static function isValid(string $json): bool
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }
}