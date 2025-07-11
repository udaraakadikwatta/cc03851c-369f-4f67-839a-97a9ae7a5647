<?php
namespace App\Models;

Use Exception;

class JsonLoader
{
    /**
     * Load and decode a JSON file from the data directory.
     *
     * @param string $filename
     * @return array 
     *
     * @throws Exception
     */
    public static function load(string $filename): array
    {
        $path = __DIR__ . '/../../data/' . $filename;
        if (!file_exists($path)) {
            throw new Exception("File not found: $path");
        }

        $data = json_decode(file_get_contents($path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in $filename");
        }

        return $data;
    }
}